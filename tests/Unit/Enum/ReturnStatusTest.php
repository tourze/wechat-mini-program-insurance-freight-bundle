<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;

class ReturnStatusTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals(0, ReturnStatus::Waiting->value);
        $this->assertEquals(1, ReturnStatus::Appointment->value);
        $this->assertEquals(2, ReturnStatus::Filled->value);
    }
    
    public function testEnumCases(): void
    {
        $cases = ReturnStatus::cases();
        $this->assertCount(3, $cases);
        $this->assertContainsOnlyInstancesOf(ReturnStatus::class, $cases);
    }
}