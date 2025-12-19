<?php

namespace WechatMiniProgramInsuranceFreightBundle\Service;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
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
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_insurance_freight')]
final class InsuranceFreightService
{
    public function __construct(
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param array<string, mixed> $item
     */
    public function overrideOrderInfo(InsuranceOrder $order, array $item): void
    {
        $this->setBasicOrderFields($order, $item);
        $this->setMonetaryFields($order, $item);
        $this->setStatusFields($order, $item);
        $this->setTimeFields($order, $item);
        $this->setBooleanFields($order, $item);
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setBasicOrderFields(InsuranceOrder $order, array $item): void
    {
        $order->setPolicyNo($this->extractStringValue($item, 'policy_no', ''));
        $order->setOrderNo($this->extractStringValue($item, 'order_no', ''));
        $order->setReportNo($this->extractStringValue($item, 'report_no', ''));
        $order->setDeliveryNo($this->extractStringValue($item, 'delivery_no', ''));
        $order->setRefundDeliveryNo($this->extractStringValue($item, 'refund_delivery_no', ''));
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setMonetaryFields(InsuranceOrder $order, array $item): void
    {
        $order->setPremium($this->safeIntCast($item['premium'] ?? 0));
        $order->setEstimateAmount($this->safeIntCast($item['estimate_amount'] ?? 0));
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setStatusFields(InsuranceOrder $order, array $item): void
    {
        $status = $item['status'] ?? null;
        if (is_int($status) || is_string($status)) {
            $order->setStatus(InsuranceOrderStatus::from($status));
        }

        $payFailReason = $item['pay_fail_reason'] ?? null;
        $order->setPayFailReason(is_string($payFailReason) ? $payFailReason : null);
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setTimeFields(InsuranceOrder $order, array $item): void
    {
        $payFinishTime = $item['pay_finish_time'] ?? 0;
        $order->setPayFinishTime(is_numeric($payFinishTime) && $payFinishTime > 0
            ? CarbonImmutable::createFromTimestamp((int) $payFinishTime, date_default_timezone_get())
            : null);
    }

    /**
     * @param array<string, mixed> $item
     */
    private function setBooleanFields(InsuranceOrder $order, array $item): void
    {
        $order->setHomePickUp((bool) ($item['is_home_pick_up'] ?? false));
    }

    /**
     * @param array<string, mixed> $data
     */
    private function extractStringValue(array $data, string $key, string $default): string
    {
        $value = $data[$key] ?? null;

        return is_string($value) ? $value : $default;
    }

    private function safeIntCast(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return 0;
    }

    /**
     * 同步单个保险单
     */
    public function syncInsuranceOrder(InsuranceOrder $order): void
    {
        $response = $this->fetchInsuranceOrderData($order);
        if (null === $response) {
            return;
        }

        if (!isset($response['list']) || !is_array($response['list']) || !isset($response['list'][0]) || !is_array($response['list'][0])) {
            return;
        }

        $item = $response['list'][0];

        // 确保数组具有字符串键
        /** @var array<string, mixed> $itemData */
        $itemData = $this->ensureStringKeyedArray($item);
        $this->overrideOrderInfo($order, $itemData);

        if (isset($item['policy_no']) && is_string($item['policy_no'])) {
            $order->setPolicyNo($item['policy_no']);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /**
     * @return array<string, mixed>|null
     */
    private function fetchInsuranceOrderData(InsuranceOrder $order): ?array
    {
        $account = $order->getAccount();

        $request = new GetInsuranceOrderListRequest();
        if ($account instanceof MiniProgramInterface) {
            $request->setAccount($account);
        }
        $request->setLimit(1);
        $request->setOffset(0);
        $request->setOrderNo($order->getOrderNo());

        $response = $this->client->request($request);

        if (!is_array($response) || !isset($response['list']) || [] === $response['list'] || !is_array($response['list'])) {
            return null;
        }

        /** @var array<string, mixed> $response */
        return $response;
    }

    /**
     * @param array<mixed, mixed> $data
     * @return array<string, mixed>
     */
    private function ensureStringKeyedArray(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[(string) $key] = $value;
        }

        return $result;
    }

    /**
     * 同步退货单信息
     */
    public function syncReturnOrder(ReturnOrder $order): void
    {
        try {
            $response = $this->fetchReturnOrderData($order);
            if (null === $response) {
                return;
            }

            $this->updateReturnOrderFromResponse($order, $response);
            $this->persistReturnOrder($order);
        } catch (\Throwable $exception) {
            $this->logger->error('同步退货单信息失败', [
                'exception' => $exception,
                'order' => $order,
            ]);
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    private function fetchReturnOrderData(ReturnOrder $order): ?array
    {
        $request = new GetReturnOrderRequest();
        $account = $order->getAccount();

        if ($account instanceof MiniProgramInterface) {
            $request->setAccount($account);
        }

        $returnId = $order->getReturnId();
        if (null !== $returnId) {
            $request->setReturnId($returnId);
        }

        $response = $this->client->request($request);

        if (!is_array($response)) {
            return null;
        }

        /** @var array<string, mixed> $response */
        return $response;
    }

    /**
     * @param array<string, mixed> $response
     */
    private function updateReturnOrderFromResponse(ReturnOrder $order, array $response): void
    {
        $this->updateReturnOrderStatus($order, $response);
        $this->updateReturnOrderFields($order, $response);
    }

    /**
     * @param array<string, mixed> $response
     */
    private function updateReturnOrderStatus(ReturnOrder $order, array $response): void
    {
        $status = $response['status'] ?? null;
        if (is_int($status) || is_string($status)) {
            $order->setStatus(ReturnStatus::tryFrom($status));
        }

        $orderStatus = $response['order_status'] ?? null;
        if (is_int($orderStatus) || is_string($orderStatus)) {
            $order->setOrderStatus(ReturnOrderStatus::tryFrom($orderStatus));
        }
    }

    /**
     * @param array<string, mixed> $response
     */
    private function updateReturnOrderFields(ReturnOrder $order, array $response): void
    {
        $waybillId = $response['waybill_id'] ?? null;
        $order->setWaybillId(is_string($waybillId) ? $waybillId : null);

        $deliveryName = $response['delivery_name'] ?? null;
        $order->setDeliveryName(is_string($deliveryName) ? $deliveryName : null);

        $deliveryId = $response['delivery_id'] ?? null;
        $order->setDeliveryId(is_string($deliveryId) ? $deliveryId : null);
    }

    private function persistReturnOrder(ReturnOrder $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}
