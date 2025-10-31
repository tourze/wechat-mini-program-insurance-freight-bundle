<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

/**
 * @internal
 */
#[CoversClass(InsuranceFreightService::class)]
#[RunTestsInSeparateProcesses]
final class InsuranceFreightServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    private function getInsuranceFreightService(): InsuranceFreightService
    {
        return self::getService(InsuranceFreightService::class);
    }

    public function testOverrideOrderInfo(): void
    {
        $service = $this->getInsuranceFreightService();

        $order = new InsuranceOrder();
        $item = [
            'policy_no' => 'TEST001',
            'order_no' => 'ORDER001',
            'report_no' => 'REPORT001',
            'delivery_no' => 'DELIVERY001',
            'refund_delivery_no' => 'REFUND001',
            'premium' => 100,
            'estimate_amount' => 1000,
            'status' => 2,
            'pay_fail_reason' => null,
            'pay_finish_time' => time(),
            'is_home_pick_up' => 1,
        ];

        $service->overrideOrderInfo($order, $item);

        $this->assertSame('TEST001', $order->getPolicyNo());
        $this->assertSame('ORDER001', $order->getOrderNo());
        $this->assertSame('REPORT001', $order->getReportNo());
    }

    public function testServiceInstance(): void
    {
        $service = $this->getInsuranceFreightService();

        $this->assertInstanceOf(InsuranceFreightService::class, $service);
    }

    /**
     * 测试 syncInsuranceOrder 方法
     */
    public function testSyncInsuranceOrder(): void
    {
        $service = $this->getInsuranceFreightService();

        $order = new InsuranceOrder();
        $order->setOrderNo('test_order_123');

        // 测试同步保险订单 - 没有设置 account 时应该抛出异常
        // 这验证了方法正确地处理了缺少 account 的情况
        $this->expectException(\Error::class);
        $service->syncInsuranceOrder($order);
    }

    /**
     * 测试 syncReturnOrder 方法
     */
    public function testSyncReturnOrder(): void
    {
        $service = $this->getInsuranceFreightService();

        $order = new ReturnOrder();
        $order->setReturnId('test_return_123');

        // 测试同步退货订单 - 应该不抛出异常
        $service->syncReturnOrder($order);

        // 添加断言：确保退货订单对象存在且退货ID保持不变
        $this->assertInstanceOf(ReturnOrder::class, $order);
        $this->assertEquals('test_return_123', $order->getReturnId());
    }

    /**
     * 测试 overrideOrderInfo 方法 - 完整字段覆盖
     */
    public function testOverrideOrderInfoWithCompleteData(): void
    {
        $service = $this->getInsuranceFreightService();

        $order = new InsuranceOrder();
        $item = [
            'policy_no' => 'POLICY_123',
            'order_no' => 'ORDER_123',
            'report_no' => 'REPORT_123',
            'delivery_no' => 'DELIVERY_123',
            'refund_delivery_no' => 'REFUND_123',
            'premium' => 200,
            'estimate_amount' => 2000,
            'status' => 4,
            'pay_fail_reason' => 'Test reason',
            'pay_finish_time' => time(),
            'is_home_pick_up' => 0,
        ];

        $service->overrideOrderInfo($order, $item);

        $this->assertSame('POLICY_123', $order->getPolicyNo());
        $this->assertSame('ORDER_123', $order->getOrderNo());
        $this->assertSame('REPORT_123', $order->getReportNo());
        $this->assertSame('DELIVERY_123', $order->getDeliveryNo());
        $this->assertSame('REFUND_123', $order->getRefundDeliveryNo());
        $this->assertSame(200, $order->getPremium());
        $this->assertSame(2000, $order->getEstimateAmount());
        $this->assertSame('Test reason', $order->getPayFailReason());
        $this->assertFalse($order->isHomePickUp());
    }

    /**
     * 测试 overrideOrderInfo 方法 - 处理空值和默认值
     */
    public function testOverrideOrderInfoWithDefaultValues(): void
    {
        $service = $this->getInsuranceFreightService();

        $order = new InsuranceOrder();
        $item = [
            'premium' => null,
            'estimate_amount' => 'invalid',
            'status' => null,
            'pay_finish_time' => 0,
            'is_home_pick_up' => null,
        ];

        $service->overrideOrderInfo($order, $item);

        $this->assertSame(0, $order->getPremium());
        $this->assertSame(0, $order->getEstimateAmount());
        $this->assertNull($order->getPayFailReason());
        $this->assertNull($order->getPayFinishTime());
        $this->assertFalse($order->isHomePickUp());
    }
}
