<?php

namespace WechatMiniProgramInsuranceFreightBundle\EventSubscriber;

use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateInsuranceOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\Goods;
use WechatMiniProgramInsuranceFreightBundle\Request\Place;
use WechatMiniProgramInsuranceFreightBundle\Request\ProductInfo;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: InsuranceOrder::class)]
class InsuranceOrderListener
{
    public function __construct(
        private readonly Client $client,
        private readonly UserLoaderInterface $userLoader,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function prePersist(InsuranceOrder $obj): void
    {
        // 保存前需要先请求微信创单
        try {
            if ($obj->getOpenId() === '') {
                throw new ApiException('openid不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('openid不能为空');
        }
        
        try {
            if ($obj->getDeliveryNo() === '') {
                throw new ApiException('发货运单号不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('发货运单号不能为空');
        }
        
        try {
            if ($obj->getPayAmount() === 0) {
                throw new ApiException('支付金额不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('支付金额不能为空');
        }
        
        try {
            $obj->getPayTime();
        } catch (\Error $e) {
            throw new ApiException('支付时间不能为空');
        }
        
        try {
            if ($obj->getOrderNo() === '') {
                throw new ApiException('微信支付单号不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('微信支付单号不能为空');
        }
        
        try {
            if ($obj->getDeliveryPlaceProvince() === '') {
                throw new ApiException('发货省份不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('发货省份不能为空');
        }
        
        try {
            if ($obj->getDeliveryPlaceCity() === '') {
                throw new ApiException('发货城市不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('发货城市不能为空');
        }
        
        try {
            if ($obj->getDeliveryPlaceCounty() === '') {
                throw new ApiException('发货区不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('发货区不能为空');
        }
        
        try {
            if ($obj->getDeliveryPlaceAddress() === '') {
                throw new ApiException('发货详细地址不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('发货详细地址不能为空');
        }
        
        try {
            if ($obj->getReceiptPlaceProvince() === '') {
                throw new ApiException('收货省份不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('收货省份不能为空');
        }
        
        try {
            if ($obj->getReceiptPlaceCity() === '') {
                throw new ApiException('收货城市不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('收货城市不能为空');
        }
        
        try {
            if ($obj->getReceiptPlaceCounty() === '') {
                throw new ApiException('收货区不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('收货区不能为空');
        }
        
        try {
            if ($obj->getReceiptPlaceAddress() === '') {
                throw new ApiException('收货详细地址不能为空');
            }
        } catch (\Error $e) {
            throw new ApiException('收货详细地址不能为空');
        }

        $user = $this->userLoader->loadUserByOpenId($obj->getOpenId());
        if ($user === null) {
            throw new ApiException('找不到小程序用户');
        }
        
        // 检查用户实例是否有 getAccount 方法
        if (!method_exists($user, 'getAccount')) {
            throw new ApiException('用户实例缺少 getAccount 方法');
        }

        $request = new CreateInsuranceOrderRequest();
        /** @var \WechatMiniProgramAuthBundle\Entity\User $user */
        $request->setAccount($user->getAccount());
        $request->setOpenId($obj->getOpenId());
        $request->setDeliveryNo($obj->getDeliveryNo());
        $request->setPayAmount($obj->getPayAmount());
        $request->setPayTime($obj->getPayTime()->getTimestamp());
        $request->setOrderNo($obj->getOrderNo());
        $request->setDeliveryPlace(Place::fromArray([
            'province' => $obj->getDeliveryPlaceProvince(),
            'city' => $obj->getDeliveryPlaceCity(),
            'county' => $obj->getDeliveryPlaceCounty(),
            'address' => $obj->getDeliveryPlaceAddress(),
        ]));
        $request->setReceiptPlace(Place::fromArray([
            'province' => $obj->getReceiptPlaceProvince(),
            'city' => $obj->getReceiptPlaceCity(),
            'county' => $obj->getReceiptPlaceCounty(),
            'address' => $obj->getReceiptPlaceAddress(),
        ]));

        $productInfo = new ProductInfo();
        $productInfo->setOrderPath($obj->getOrderPath());
        foreach ($obj->getGoodsList() as $item) {
            $productInfo->addGoods(Goods::fromArray($item));
        }
        $request->setProductInfo($productInfo);

        $result = $this->client->request($request);
        if (!isset($result['policy_no'])) {
            $this->logger->error('运费险下单失败', [
                'result' => $result,
            ]);

            return;
        }
        $obj->setPolicyNo($result['policy_no']);
        $obj->setInsuranceEndDate(CarbonImmutable::parse($result['insurance_end_date']));
        $obj->setEstimateAmount($result['estimate_amount']);
        $obj->setPremium($result['premium']);
        // 创建成功就是保障中的意思
        $obj->setStatus(InsuranceOrderStatus::Securing);
    }
}
