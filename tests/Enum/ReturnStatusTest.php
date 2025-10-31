<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;

/**
 * @internal
 */
#[CoversClass(ReturnStatus::class)]
final class ReturnStatusTest extends AbstractEnumTestCase
{
    #[TestWith([ReturnStatus::Waiting, 0, '用户未填写退货信'])]
    #[TestWith([ReturnStatus::Appointment, 1, '预约上门取件'])]
    #[TestWith([ReturnStatus::Filled, 2, '填写自行寄回运单号'])]
    public function testValueAndLabel(ReturnStatus $status, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $status->value);
        $this->assertSame($expectedLabel, $status->getLabel());
    }

    public function testEnumCases(): void
    {
        $cases = ReturnStatus::cases();
        $this->assertCount(3, $cases);
    }

    public function testFromValidValue(): void
    {
        $status = ReturnStatus::from(0);
        $this->assertSame(ReturnStatus::Waiting, $status);
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ReturnStatus::from(999);
    }

    public function testTryFromValidValue(): void
    {
        $status = ReturnStatus::tryFrom(0);
        $this->assertSame(ReturnStatus::Waiting, $status);
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        $result = ReturnStatus::tryFrom(999);
        $this->assertNull($result);
    }

    public function testValueUniqueness(): void
    {
        $values = [];
        foreach (ReturnStatus::cases() as $case) {
            $this->assertNotContains($case->value, $values, 'Duplicate value found: ' . $case->value);
            $values[] = $case->value;
        }
    }

    public function testLabelUniqueness(): void
    {
        $labels = [];
        foreach (ReturnStatus::cases() as $case) {
            $label = $case->getLabel();
            $this->assertNotContains($label, $labels, 'Duplicate label found: ' . $label);
            $labels[] = $label;
        }
    }

    public function testToArray(): void
    {
        $result = ReturnStatus::Waiting->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);

        $this->assertEquals(0, $result['value']);
        $this->assertEquals('用户未填写退货信', $result['label']);
    }
}
