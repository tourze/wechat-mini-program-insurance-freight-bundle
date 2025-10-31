<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;

/**
 * @internal
 */
#[CoversClass(ReturnOrderStatus::class)]
final class ReturnOrderStatusTest extends AbstractEnumTestCase
{
    #[TestWith([ReturnOrderStatus::Ordered, 0, '已下单待揽件'])]
    #[TestWith([ReturnOrderStatus::PickedUp, 1, '已揽件'])]
    #[TestWith([ReturnOrderStatus::InTransit, 2, '运输中'])]
    #[TestWith([ReturnOrderStatus::OutForDelivery, 3, '派件中'])]
    #[TestWith([ReturnOrderStatus::Delivered, 4, '已签收'])]
    #[TestWith([ReturnOrderStatus::Exception, 5, '异常'])]
    #[TestWith([ReturnOrderStatus::ProxyDelivered, 6, '代签收'])]
    #[TestWith([ReturnOrderStatus::PickUpFailed, 7, '揽收失败'])]
    #[TestWith([ReturnOrderStatus::DeliveryFailed, 8, '签收失败（拒收，超区）'])]
    #[TestWith([ReturnOrderStatus::Cancelled, 11, '已取消'])]
    #[TestWith([ReturnOrderStatus::Returning, 13, '退件中'])]
    #[TestWith([ReturnOrderStatus::Returned, 14, '已退件'])]
    #[TestWith([ReturnOrderStatus::Unknown, 99, '未知'])]
    public function testValueAndLabel(ReturnOrderStatus $status, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $status->value);
        $this->assertSame($expectedLabel, $status->getLabel());
    }

    public function testEnumCases(): void
    {
        $cases = ReturnOrderStatus::cases();
        $this->assertCount(13, $cases);
    }

    public function testFromValidValue(): void
    {
        $status = ReturnOrderStatus::from(0);
        $this->assertSame(ReturnOrderStatus::Ordered, $status);
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ReturnOrderStatus::from(999);
    }

    public function testTryFromValidValue(): void
    {
        $status = ReturnOrderStatus::tryFrom(0);
        $this->assertSame(ReturnOrderStatus::Ordered, $status);
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        $result = ReturnOrderStatus::tryFrom(999);
        $this->assertNull($result);
    }

    public function testValueUniqueness(): void
    {
        $values = [];
        foreach (ReturnOrderStatus::cases() as $case) {
            $this->assertNotContains($case->value, $values, 'Duplicate value found: ' . $case->value);
            $values[] = $case->value;
        }
    }

    public function testLabelUniqueness(): void
    {
        $labels = [];
        foreach (ReturnOrderStatus::cases() as $case) {
            $label = $case->getLabel();
            $this->assertNotContains($label, $labels, 'Duplicate label found: ' . $label);
            $labels[] = $label;
        }
    }

    public function testToArray(): void
    {
        $result = ReturnOrderStatus::Ordered->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);

        $this->assertEquals(0, $result['value']);
        $this->assertEquals('已下单待揽件', $result['label']);
    }
}
