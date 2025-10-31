<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

/**
 * @internal
 */
#[CoversClass(InsuranceOrderStatus::class)]
final class InsuranceOrderStatusTest extends AbstractEnumTestCase
{
    #[TestWith([InsuranceOrderStatus::Securing, 2, '保障中'])]
    #[TestWith([InsuranceOrderStatus::Claiming, 4, '理赔中'])]
    #[TestWith([InsuranceOrderStatus::ClaimSuccessful, 5, '理赔成功'])]
    #[TestWith([InsuranceOrderStatus::ClaimFailed, 6, '理赔失败'])]
    #[TestWith([InsuranceOrderStatus::InsuranceExpired, 7, '投保过期'])]
    public function testValueAndLabel(InsuranceOrderStatus $status, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $status->value);
        $this->assertSame($expectedLabel, $status->getLabel());
    }

    public function testEnumCases(): void
    {
        $cases = InsuranceOrderStatus::cases();
        $this->assertCount(5, $cases);
    }

    public function testFromValidValue(): void
    {
        $status = InsuranceOrderStatus::from(2);
        $this->assertSame(InsuranceOrderStatus::Securing, $status);
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        InsuranceOrderStatus::from(999);
    }

    public function testTryFromValidValue(): void
    {
        $status = InsuranceOrderStatus::tryFrom(2);
        $this->assertSame(InsuranceOrderStatus::Securing, $status);
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        $result = InsuranceOrderStatus::tryFrom(999);
        $this->assertNull($result);
    }

    public function testValueUniqueness(): void
    {
        $values = [];
        foreach (InsuranceOrderStatus::cases() as $case) {
            $this->assertNotContains($case->value, $values, 'Duplicate value found: ' . $case->value);
            $values[] = $case->value;
        }
    }

    public function testLabelUniqueness(): void
    {
        $labels = [];
        foreach (InsuranceOrderStatus::cases() as $case) {
            $label = $case->getLabel();
            $this->assertNotContains($label, $labels, 'Duplicate label found: ' . $label);
            $labels[] = $label;
        }
    }

    public function testToArray(): void
    {
        $result = InsuranceOrderStatus::Securing->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);

        $this->assertEquals(2, $result['value']);
        $this->assertEquals('保障中', $result['label']);
    }
}
