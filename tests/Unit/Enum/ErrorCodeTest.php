<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\ErrorCode;

class ErrorCodeTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals(2, ErrorCode::MissingParameter->value);
        $this->assertEquals(1010, ErrorCode::InsuranceTimeError->value);
        $this->assertEquals(4001, ErrorCode::InsufficientBalance->value);
    }
    
    public function testEnumCases(): void
    {
        $cases = ErrorCode::cases();
        $this->assertCount(16, $cases);
        $this->assertContainsOnlyInstancesOf(ErrorCode::class, $cases);
    }
}