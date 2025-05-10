<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

class InsuranceOrderStatusTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals(2, InsuranceOrderStatus::Securing->value);
        $this->assertEquals(4, InsuranceOrderStatus::Claiming->value);
        $this->assertEquals(5, InsuranceOrderStatus::ClaimSuccessful->value);
        $this->assertEquals(6, InsuranceOrderStatus::ClaimFailed->value);
        $this->assertEquals(7, InsuranceOrderStatus::InsuranceExpired->value);
    }
    
    public function testGetLabel(): void
    {
        $this->assertEquals('保障中', InsuranceOrderStatus::Securing->getLabel());
        $this->assertEquals('理赔中', InsuranceOrderStatus::Claiming->getLabel());
        $this->assertEquals('理赔成功', InsuranceOrderStatus::ClaimSuccessful->getLabel());
        $this->assertEquals('理赔失败', InsuranceOrderStatus::ClaimFailed->getLabel());
        $this->assertEquals('投保过期', InsuranceOrderStatus::InsuranceExpired->getLabel());
    }
} 