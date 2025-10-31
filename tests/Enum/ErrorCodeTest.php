<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\ErrorCode;

/**
 * @internal
 */
#[CoversClass(ErrorCode::class)]
final class ErrorCodeTest extends AbstractEnumTestCase
{
    #[TestWith([ErrorCode::MissingParameter, 2, '缺少必要参数'])]
    #[TestWith([ErrorCode::InsuranceTimeError, 1010, '投保时间错误'])]
    #[TestWith([ErrorCode::InsufficientBalance, 4001, '余额不足'])]
    public function testValueAndLabel(ErrorCode $errorCode, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $errorCode->value);
        $this->assertSame($expectedLabel, $errorCode->getLabel());
    }

    public function testEnumCases(): void
    {
        $cases = ErrorCode::cases();
        $this->assertCount(16, $cases);
    }

    public function testFromValidValue(): void
    {
        $errorCode = ErrorCode::from(2);
        $this->assertSame(ErrorCode::MissingParameter, $errorCode);
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ErrorCode::from(999);
    }

    public function testTryFromValidValue(): void
    {
        $errorCode = ErrorCode::tryFrom(2);
        $this->assertSame(ErrorCode::MissingParameter, $errorCode);
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        $result = ErrorCode::tryFrom(999);
        $this->assertNull($result);
    }

    public function testValueUniqueness(): void
    {
        $values = [];
        foreach (ErrorCode::cases() as $case) {
            $this->assertNotContains($case->value, $values, 'Duplicate value found: ' . $case->value);
            $values[] = $case->value;
        }
    }

    public function testLabelUniqueness(): void
    {
        $labels = [];
        foreach (ErrorCode::cases() as $case) {
            $label = $case->getLabel();
            $this->assertNotContains($label, $labels, 'Duplicate label found: ' . $label);
            $labels[] = $label;
        }
    }

    public function testToArray(): void
    {
        $result = ErrorCode::MissingParameter->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);

        $this->assertEquals(2, $result['value']);
        $this->assertEquals('缺少必要参数', $result['label']);
    }
}
