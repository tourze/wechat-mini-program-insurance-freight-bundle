<?php

namespace WechatMiniProgramInsuranceFreightBundle\Service;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;
use WechatMiniProgramInsuranceFreightBundle\Request\GetInsuranceOrderListRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\GetReturnOrderRequest;

/**
 * 因为大部分逻辑都跟小程序一致的，所以我们直接继承算了
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html#_6-%E5%BC%80%E5%8F%91%E6%96%87%E6%A1%A3
 */
class InsuranceFreightService
{
    public function __construct(
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function overrideOrderInfo(InsuranceOrder $order, array $item): void
    {
        $order->setPolicyNo($item['policy_no']);
        $order->setOrderNo($item['order_no']);
        $order->setReportNo($item['report_no']);
        $order->setDeliveryNo($item['delivery_no']);
        $order->setRefundDeliveryNo($item['refund_delivery_no']);
        $order->setPremium($item['premium']);
        $order->setEstimateAmount($item['estimate_amount']);
        $order->setStatus(InsuranceOrderStatus::from($item['status']));
        $order->setPayFailReason($item['pay_fail_reason'] ?? null);
        $order->setPayFinishTime(isset($item['pay_finish_time']) && $item['pay_finish_time'] > 0
            ? CarbonImmutable::createFromTimestamp($item['pay_finish_time'], date_default_timezone_get())
            : null);
        $order->setHomePickUp((bool) $item['is_home_pick_up']);
    }

    /**
     * 同步单个保险单
     */
    public function syncInsuranceOrder(InsuranceOrder $order): void
    {
        $account = $order->getAccount();

        $request = new GetInsuranceOrderListRequest();
        $request->setAccount($account);
        $request->setLimit(1);
        $request->setOffset(0);
        $request->setOrderNo($order->getOrderNo());
        $response = $this->client->request($request);

        if (empty($response['list'])) {
            return;
        }
        $item = $response['list'][0];

        $this->overrideOrderInfo($order, $item);
        $order->setPolicyNo($item['policy_no']);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /**
     * 同步退货单信息
     */
    public function syncReturnOrder(ReturnOrder $order): void
    {
        try {
            $request = new GetReturnOrderRequest();
            $request->setAccount($order->getAccount());
            $request->setReturnId($order->getReturnId());
            $response = $this->client->request($request);
            $order->setStatus(ReturnStatus::tryFrom($response['status']));
            $order->setWaybillId($response['waybill_id'] ?? null);
            $order->setOrderStatus(ReturnOrderStatus::tryFrom($response['order_status']));
            $order->setDeliveryName($response['delivery_name'] ?? null);
            $order->setDeliveryId($response['delivery_id'] ?? null);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        } catch (\Throwable $exception) {
            $this->logger->error('同步退货单信息失败', [
                'exception' => $exception,
                'order' => $order,
            ]);
        }
    }
}
