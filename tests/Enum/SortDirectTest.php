<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\SortDirect;

/**
 * @internal
 */
#[CoversClass(SortDirect::class)]
final class SortDirectTest extends AbstractEnumTestCase
{
    #[TestWith([SortDirect::ASC, 0, 'create_time正序'])]
    #[TestWith([SortDirect::DESC, 1, 'create_time倒序'])]
    public function testValueAndLabel(SortDirect $sortDirect, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $sortDirect->value);
        $this->assertSame($expectedLabel, $sortDirect->getLabel());
    }

    public function testEnumCases(): void
    {
        $cases = SortDirect::cases();
        $this->assertCount(2, $cases);
    }

    public function testFromValidValue(): void
    {
        $sortDirect = SortDirect::from(0);
        $this->assertSame(SortDirect::ASC, $sortDirect);
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        SortDirect::from(999);
    }

    public function testTryFromValidValue(): void
    {
        $sortDirect = SortDirect::tryFrom(0);
        $this->assertSame(SortDirect::ASC, $sortDirect);
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        $result = SortDirect::tryFrom(999);
        $this->assertNull($result);
    }

    public function testValueUniqueness(): void
    {
        $values = [];
        foreach (SortDirect::cases() as $case) {
            $this->assertNotContains($case->value, $values, 'Duplicate value found: ' . $case->value);
            $values[] = $case->value;
        }
    }

    public function testLabelUniqueness(): void
    {
        $labels = [];
        foreach (SortDirect::cases() as $case) {
            $label = $case->getLabel();
            $this->assertNotContains($label, $labels, 'Duplicate label found: ' . $label);
            $labels[] = $label;
        }
    }

    public function testToArray(): void
    {
        $result = SortDirect::ASC->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);

        $this->assertEquals(0, $result['value']);
        $this->assertEquals('create_time正序', $result['label']);
    }
}
