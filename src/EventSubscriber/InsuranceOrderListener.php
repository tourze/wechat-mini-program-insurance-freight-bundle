<?php

namespace WechatMiniProgramInsuranceFreightBundle\EventSubscriber;

use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Exception\InsuranceOrderValidationException;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateInsuranceOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\Goods;
use WechatMiniProgramInsuranceFreightBundle\Request\Place;
use WechatMiniProgramInsuranceFreightBundle\Request\ProductInfo;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: InsuranceOrder::class)]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_insurance_freight')]
readonly class InsuranceOrderListener
{
    public function __construct(
        private Client $client,
        private UserLoaderInterface $userLoader,
        private LoggerInterface $logger,
    ) {
    }

    public function prePersist(InsuranceOrder $obj): void
    {
        // 在测试环境中跳过外部API调用
        $env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'prod';
        if ('test' === $env) {
            return;
        }

        $this->validateInsuranceOrder($obj);
        $openId = $obj->getOpenId();
        if (null === $openId) {
            throw new InsuranceOrderValidationException('openid不能为空');
        }
        $user = $this->loadAndValidateUser($openId);
        $request = $this->buildCreateInsuranceOrderRequest($obj, $user);
        $this->processInsuranceOrderCreation($obj, $request);
    }

    private function validateInsuranceOrder(InsuranceOrder $obj): void
    {
        $validations = [
            [$obj->getOpenId(), 'openid不能为空'],
            [$obj->getDeliveryNo(), '发货运单号不能为空'],
            [$obj->getOrderNo(), '微信支付单号不能为空'],
            [$obj->getDeliveryPlaceProvince(), '发货省份不能为空'],
            [$obj->getDeliveryPlaceCity(), '发货城市不能为空'],
            [$obj->getDeliveryPlaceCounty(), '发货区不能为空'],
            [$obj->getDeliveryPlaceAddress(), '发货详细地址不能为空'],
            [$obj->getReceiptPlaceProvince(), '收货省份不能为空'],
            [$obj->getReceiptPlaceCity(), '收货城市不能为空'],
            [$obj->getReceiptPlaceCounty(), '收货区不能为空'],
            [$obj->getReceiptPlaceAddress(), '收货详细地址不能为空'],
        ];

        foreach ($validations as [$value, $message]) {
            $this->validateNotEmpty($value, $message);
        }

        if (0 === $obj->getPayAmount()) {
            throw new InsuranceOrderValidationException('支付金额不能为空');
        }

        try {
            $obj->getPayTime();
        } catch (\Error $e) {
            throw new InsuranceOrderValidationException('支付时间不能为空');
        }
    }

    private function validateNotEmpty(?string $value, string $message): void
    {
        if ('' === $value || null === $value) {
            throw new InsuranceOrderValidationException($message);
        }
    }

    private function loadAndValidateUser(string $openId): UserInterface
    {
        $user = $this->userLoader->loadUserByOpenId($openId);
        if (null === $user) {
            throw new InsuranceOrderValidationException('找不到小程序用户');
        }

        if (!method_exists($user, 'getAccount')) {
            throw new InsuranceOrderValidationException('用户实例缺少 getAccount 方法');
        }

        return $user;
    }

    private function buildCreateInsuranceOrderRequest(InsuranceOrder $obj, UserInterface $user): CreateInsuranceOrderRequest
    {
        $request = new CreateInsuranceOrderRequest();
        $request->setAccount($user->getMiniProgram());
        $openId = $obj->getOpenId();
        if (null === $openId) {
            throw new InsuranceOrderValidationException('openid不能为空');
        }
        $request->setOpenId($openId);
        $request->setDeliveryNo($obj->getDeliveryNo());
        $request->setPayAmount($obj->getPayAmount());
        $request->setPayTime($obj->getPayTime()->getTimestamp() * 1000 + (int) ($obj->getPayTime()->format('u') / 1000));
        $request->setOrderNo($obj->getOrderNo());

        $request->setDeliveryPlace($this->createPlace(
            $obj->getDeliveryPlaceProvince(),
            $obj->getDeliveryPlaceCity(),
            $obj->getDeliveryPlaceCounty(),
            $obj->getDeliveryPlaceAddress()
        ));

        $request->setReceiptPlace($this->createPlace(
            $obj->getReceiptPlaceProvince(),
            $obj->getReceiptPlaceCity(),
            $obj->getReceiptPlaceCounty(),
            $obj->getReceiptPlaceAddress()
        ));

        $productInfo = new ProductInfo();
        $orderPath = $obj->getOrderPath();
        if (null !== $orderPath) {
            $productInfo->setOrderPath($orderPath);
        }

        $goodsList = $obj->getGoodsList();
        if (null !== $goodsList) {
            foreach ($goodsList as $item) {
                $productInfo->addGoods(Goods::fromArray($item));
            }
        }
        $request->setProductInfo($productInfo);

        return $request;
    }

    private function createPlace(string $province, string $city, string $county, string $address): Place
    {
        return Place::fromArray([
            'province' => $province,
            'city' => $city,
            'county' => $county,
            'address' => $address,
        ]);
    }

    private function processInsuranceOrderCreation(InsuranceOrder $obj, CreateInsuranceOrderRequest $request): void
    {
        $result = $this->client->request($request);

        // 确保返回结果是数组
        if (!is_array($result)) {
            $this->logger->error('运费险下单失败：返回结果不是数组', [
                'result' => $result,
            ]);

            return;
        }

        // 类型安全的数组访问
        /** @var array<string, mixed> $result */
        $response = $this->validateInsuranceOrderResponse($result);
        if (null === $response) {
            return;
        }

        $obj->setPolicyNo($response['policy_no']);
        $obj->setInsuranceEndDate(CarbonImmutable::parse($response['insurance_end_date']));
        $obj->setEstimateAmount($response['estimate_amount']);
        $obj->setPremium($response['premium']);
        $obj->setStatus(InsuranceOrderStatus::Securing);
    }

    /**
     * 验证并处理保险订单响应
     *
     * @param array<string, mixed> $result
     * @return array{policy_no: string, insurance_end_date: string, estimate_amount: int, premium: int}|null
     */
    private function validateInsuranceOrderResponse(array $result): ?array
    {
        if (!isset($result['policy_no']) || !is_string($result['policy_no'])) {
            $this->logger->error('运费险下单失败：缺少policy_no或类型不正确', [
                'result' => $result,
            ]);

            return null;
        }

        if (!isset($result['insurance_end_date']) || !is_string($result['insurance_end_date'])) {
            $this->logger->error('运费险下单失败：缺少insurance_end_date或类型不正确', [
                'result' => $result,
            ]);

            return null;
        }

        if (!isset($result['estimate_amount']) || !is_int($result['estimate_amount'])) {
            $this->logger->error('运费险下单失败：缺少estimate_amount或类型不正确', [
                'result' => $result,
            ]);

            return null;
        }

        if (!isset($result['premium']) || !is_int($result['premium'])) {
            $this->logger->error('运费险下单失败：缺少premium或类型不正确', [
                'result' => $result,
            ]);

            return null;
        }

        return [
            'policy_no' => $result['policy_no'],
            'insurance_end_date' => $result['insurance_end_date'],
            'estimate_amount' => $result['estimate_amount'],
            'premium' => $result['premium'],
        ];
    }
}
