<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;

class ReturnOrderStatusTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals(0, ReturnOrderStatus::Ordered->value);
        $this->assertEquals(4, ReturnOrderStatus::Delivered->value);
        $this->assertEquals(5, ReturnOrderStatus::Exception->value);
    }
    
    public function testEnumCases(): void
    {
        $cases = ReturnOrderStatus::cases();
        $this->assertCount(13, $cases);
        $this->assertContainsOnlyInstancesOf(ReturnOrderStatus::class, $cases);
    }
}