<?php

namespace WechatMiniProgramInsuranceFreightBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Request\Address;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateReturnOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\UnbindReturnOrderRequest;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ReturnOrder::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: ReturnOrder::class)]
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
        $user = $this->userLoader->loadUserByOpenId($obj->getOpenId());
        if (!$user) {
            throw new ApiException('找不到小程序用户');
        }

        $request = new CreateReturnOrderRequest();
        $request->setAccount($user->getAccount());
        $request->setShopOrderId($obj->getShopOrderId());
        $request->setBizAddress(Address::fromArray($obj->getBizAddress()));
        $request->setUserAddress(Address::fromArray($obj->getUserAddress()));
        $request->setOpenid($obj->getOpenId());
        $request->setOrderPath($obj->getOrderPath());
        $request->setWxPayId($obj->getWxPayId());
        $request->setGoodsList($obj->getGoodsList());

        $result = $this->client->request($request);
        if (isset($result['return_id'])) {
            // 保存这个退货ID
            $obj->setReturnId($result['return_id']);
        } else {
            $this->logger->error('运费险保存退货ID失败', [
                'result' => $result,
            ]);
        }
    }

    public function postRemove(ReturnOrder $obj): void
    {
        if (!$obj->getReturnId()) {
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
