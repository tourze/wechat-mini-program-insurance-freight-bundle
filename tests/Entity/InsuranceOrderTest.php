<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

/**
 * @internal
 */
#[CoversClass(InsuranceOrder::class)]
final class InsuranceOrderTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new InsuranceOrder();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'openId' => ['openId', 'test_value'],
            'orderNo' => ['orderNo', 'test_value'],
            'status' => ['status', InsuranceOrderStatus::Securing],
            'payAmount' => ['payAmount', 123],
            'estimateAmount' => ['estimateAmount', 123],
            'premium' => ['premium', 123],
            'deliveryNo' => ['deliveryNo', 'test_value'],
            'deliveryPlaceProvince' => ['deliveryPlaceProvince', 'test_value'],
            'deliveryPlaceCity' => ['deliveryPlaceCity', 'test_value'],
            'deliveryPlaceCounty' => ['deliveryPlaceCounty', 'test_value'],
            'deliveryPlaceAddress' => ['deliveryPlaceAddress', 'test_value'],
            'receiptPlaceProvince' => ['receiptPlaceProvince', 'test_value'],
            'receiptPlaceCity' => ['receiptPlaceCity', 'test_value'],
            'receiptPlaceCounty' => ['receiptPlaceCounty', 'test_value'],
            'receiptPlaceAddress' => ['receiptPlaceAddress', 'test_value'],
            'policyNo' => ['policyNo', 'test_value'],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testInsuranceOrderSpecificSetters(): void
    {
        $insuranceOrder = new InsuranceOrder();

        $insuranceOrder->setOpenId('test_open_id');
        $this->assertEquals('test_open_id', $insuranceOrder->getOpenId());

        $insuranceOrder->setOrderNo('test_order_no');
        $this->assertEquals('test_order_no', $insuranceOrder->getOrderNo());

        $insuranceOrder->setStatus(InsuranceOrderStatus::Securing);
        $this->assertEquals(InsuranceOrderStatus::Securing, $insuranceOrder->getStatus());

        $insuranceOrder->setPremium(100);
        $this->assertEquals(100, $insuranceOrder->getPremium());

        $insuranceOrder->setPayAmount(1000);
        $this->assertEquals(1000, $insuranceOrder->getPayAmount());

        $insuranceOrder->setEstimateAmount(2000);
        $this->assertEquals(2000, $insuranceOrder->getEstimateAmount());

        $payTime = new \DateTimeImmutable();
        $insuranceOrder->setPayTime($payTime);
        $this->assertEquals($payTime, $insuranceOrder->getPayTime());

        $insuranceOrder->setDeliveryNo('SF1234567890');
        $this->assertEquals('SF1234567890', $insuranceOrder->getDeliveryNo());
    }
}
