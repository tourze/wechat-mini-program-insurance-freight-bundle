<?php

namespace WechatMiniProgramInsuranceFreightBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnOrderValidationException;
use WechatMiniProgramInsuranceFreightBundle\Request\Address;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateReturnOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\UnbindReturnOrderRequest;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ReturnOrder::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: ReturnOrder::class)]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_insurance_freight')]
class ReturnOrderListener
{
    public function __construct(
        private readonly Client $client,
        private readonly UserLoaderInterface $userLoader,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function prePersist(ReturnOrder $obj): void
    {
        if (($_ENV['APP_ENV'] ?? 'prod') === 'test') {
            return;
        }

        $user = $this->validateAndGetUser($obj);
        $request = $this->buildCreateReturnOrderRequest($obj, $user);

        $result = $this->client->request($request);

        // 确保结果是数组才处理
        if (is_array($result)) {
            /** @var array<string, mixed> $result */
            $this->handleApiResult($obj, $result);
        } else {
            $this->logger->error('运费险创建退货单失败：返回结果不是数组', [
                'result' => $result,
            ]);
        }
    }

    private function validateAndGetUser(ReturnOrder $obj): User
    {
        $openId = $obj->getOpenId();
        if (null === $openId) {
            throw new ReturnOrderValidationException('openid不能为空');
        }

        $user = $this->userLoader->loadUserByOpenId($openId);
        if (null === $user) {
            throw new ReturnOrderValidationException('找不到小程序用户');
        }

        if (!$user instanceof User) {
            throw new ReturnOrderValidationException('用户类型错误，期望 User 实例');
        }

        return $user;
    }

    private function buildCreateReturnOrderRequest(ReturnOrder $obj, User $user): CreateReturnOrderRequest
    {
        $request = new CreateReturnOrderRequest();

        $account = $user->getAccount();
        if (null === $account) {
            throw new ReturnOrderValidationException('用户账户信息不能为空');
        }
        $request->setAccount($account);

        $this->setRequiredFields($request, $obj);
        $this->setAddressFields($request, $obj);

        return $request;
    }

    private function setRequiredFields(CreateReturnOrderRequest $request, ReturnOrder $obj): void
    {
        $shopOrderId = $obj->getShopOrderId();
        if (null === $shopOrderId) {
            throw new ReturnOrderValidationException('店铺订单ID不能为空');
        }
        $request->setShopOrderId($shopOrderId);

        $openId = $obj->getOpenId();
        if (null === $openId) {
            throw new ReturnOrderValidationException('OpenID不能为空');
        }
        $request->setOpenId($openId);

        $orderPath = $obj->getOrderPath();
        if (null === $orderPath) {
            throw new ReturnOrderValidationException('订单路径不能为空');
        }
        $request->setOrderPath($orderPath);

        $wxPayId = $obj->getWxPayId();
        if (null === $wxPayId) {
            throw new ReturnOrderValidationException('微信支付ID不能为空');
        }
        $request->setWxPayId($wxPayId);

        $goodsList = $obj->getGoodsList();
        if (null === $goodsList) {
            throw new ReturnOrderValidationException('商品列表不能为空');
        }
        $request->setGoodsList($goodsList);
    }

    private function setAddressFields(CreateReturnOrderRequest $request, ReturnOrder $obj): void
    {
        $bizAddress = $obj->getBizAddress();
        if ([] === $bizAddress) {
            throw new ReturnOrderValidationException('商家地址不能为空');
        }
        $request->setBizAddress(Address::fromArray($bizAddress));

        $userAddress = $obj->getUserAddress();
        if ([] === $userAddress) {
            throw new ReturnOrderValidationException('用户地址不能为空');
        }
        $request->setUserAddress(Address::fromArray($userAddress));
    }

    /**
     * @param array<string, mixed> $result
     */
    private function handleApiResult(ReturnOrder $obj, array $result): void
    {
        if (isset($result['return_id']) && is_string($result['return_id'])) {
            $obj->setReturnId($result['return_id']);
        } else {
            $this->logger->error('运费险保存退货ID失败：缺少return_id或类型不正确', [
                'result' => $result,
                'return_id_exists' => isset($result['return_id']),
                'return_id_type' => isset($result['return_id']) ? gettype($result['return_id']) : 'not set',
            ]);
        }
    }

    public function postRemove(ReturnOrder $obj): void
    {
        if (null === $obj->getReturnId() || '' === $obj->getReturnId()) {
            return;
        }

        try {
            $request = new UnbindReturnOrderRequest();
            $request->setReturnId($obj->getReturnId());
            $this->client->asyncRequest($request);
        } catch (\Throwable $exception) {
            $this->logger->error('运费险删除ReturnOrder并同步删除远程时失败', [
                'returnOrder' => $obj,
                'exception' => $exception,
            ]);
        }
    }
}
