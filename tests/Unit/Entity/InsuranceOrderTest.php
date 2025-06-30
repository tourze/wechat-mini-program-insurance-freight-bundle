<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

class InsuranceOrderTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $insuranceOrder = new InsuranceOrder();
        
        $insuranceOrder->setOpenId('test_open_id');
        $this->assertEquals('test_open_id', $insuranceOrder->getOpenId());
        
        $insuranceOrder->setOrderNo('test_order_no');
        $this->assertEquals('test_order_no', $insuranceOrder->getOrderNo());
        
        $insuranceOrder->setStatus(InsuranceOrderStatus::Securing);
        $this->assertEquals(InsuranceOrderStatus::Securing, $insuranceOrder->getStatus());
        
        $insuranceOrder->setPremium(100);
        $this->assertEquals(100, $insuranceOrder->getPremium());
        
        $insuranceOrder->setPayAmount(1000);
        $this->assertEquals(1000, $insuranceOrder->getPayAmount());
        
        $insuranceOrder->setEstimateAmount(2000);
        $this->assertEquals(2000, $insuranceOrder->getEstimateAmount());
        
        $payTime = new \DateTimeImmutable();
        $insuranceOrder->setPayTime($payTime);
        $this->assertEquals($payTime, $insuranceOrder->getPayTime());
        
        $insuranceOrder->setDeliveryNo('SF1234567890');
        $this->assertEquals('SF1234567890', $insuranceOrder->getDeliveryNo());
    }
}