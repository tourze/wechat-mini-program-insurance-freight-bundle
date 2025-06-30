<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\SortDirect;

class SortDirectTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals(0, SortDirect::ASC->value);
        $this->assertEquals(1, SortDirect::DESC->value);
    }
    
    public function testEnumCases(): void
    {
        $cases = SortDirect::cases();
        $this->assertCount(2, $cases);
        $this->assertContainsOnlyInstancesOf(SortDirect::class, $cases);
    }
}