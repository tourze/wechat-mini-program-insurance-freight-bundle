<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;

class ReturnOrderTest extends TestCase
{
    private ReturnOrder $returnOrder;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->returnOrder = new ReturnOrder();
    }
    
    public function testReturnId_withValidData(): void
    {
        $returnId = 'R2021123456789';
        $this->returnOrder->setReturnId($returnId);
        $this->assertEquals($returnId, $this->returnOrder->getReturnId());
    }
    
    public function testAccount_withValidAccount(): void
    {
        $account = $this->createMock(Account::class);
        $this->returnOrder->setAccount($account);
        $this->assertSame($account, $this->returnOrder->getAccount());
    }
    
    public function testAccount_withNull(): void
    {
        $this->returnOrder->setAccount(null);
        $this->assertNull($this->returnOrder->getAccount());
    }
    
    public function testStatus_withValidStatus(): void
    {
        $status = ReturnStatus::Appointment;
        $this->returnOrder->setStatus($status);
        $this->assertSame($status, $this->returnOrder->getStatus());
    }
    
    public function testStatus_withNull(): void
    {
        $this->returnOrder->setStatus(null);
        $this->assertNull($this->returnOrder->getStatus());
    }
    
    public function testOrderStatus_withValidStatus(): void
    {
        $orderStatus = ReturnOrderStatus::Ordered;
        $this->returnOrder->setOrderStatus($orderStatus);
        $this->assertSame($orderStatus, $this->returnOrder->getOrderStatus());
    }
    
    public function testOrderStatus_withNull(): void
    {
        $this->returnOrder->setOrderStatus(null);
        $this->assertNull($this->returnOrder->getOrderStatus());
    }
    
    public function testWaybillId_withValidData(): void
    {
        $waybillId = 'SF1234567890';
        $this->returnOrder->setWaybillId($waybillId);
        $this->assertEquals($waybillId, $this->returnOrder->getWaybillId());
    }
    
    public function testWaybillId_withNull(): void
    {
        $this->returnOrder->setWaybillId(null);
        $this->assertNull($this->returnOrder->getWaybillId());
    }
    
    public function testDeliveryId_withValidData(): void
    {
        $deliveryId = 'SF';
        $this->returnOrder->setDeliveryId($deliveryId);
        $this->assertEquals($deliveryId, $this->returnOrder->getDeliveryId());
    }
    
    public function testDeliveryId_withNull(): void
    {
        $this->returnOrder->setDeliveryId(null);
        $this->assertNull($this->returnOrder->getDeliveryId());
    }
    
    public function testDeliveryName_withValidData(): void
    {
        $deliveryName = '顺丰速运';
        $this->returnOrder->setDeliveryName($deliveryName);
        $this->assertEquals($deliveryName, $this->returnOrder->getDeliveryName());
    }
    
    public function testDeliveryName_withNull(): void
    {
        $this->returnOrder->setDeliveryName(null);
        $this->assertNull($this->returnOrder->getDeliveryName());
    }
    
    public function testCreateTime_withValidDateTime(): void
    {
        $createTime = new \DateTimeImmutable('2023-01-01 10:00:00');
        $this->returnOrder->setCreateTime($createTime);
        $this->assertSame($createTime, $this->returnOrder->getCreateTime());
    }
    
    public function testUpdateTime_withValidDateTime(): void
    {
        $updateTime = new \DateTimeImmutable('2023-01-02 11:00:00');
        $this->returnOrder->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $this->returnOrder->getUpdateTime());
    }
    
    public function testShopOrderId_withValidData(): void
    {
        $shopOrderId = 'SHOP2021123456789';
        $this->returnOrder->setShopOrderId($shopOrderId);
        $this->assertEquals($shopOrderId, $this->returnOrder->getShopOrderId());
    }
    
    public function testOpenId_withValidData(): void
    {
        $openId = 'o1234567890abcdef';
        $this->returnOrder->setOpenId($openId);
        $this->assertEquals($openId, $this->returnOrder->getOpenId());
    }
    
    public function testOrderPath_withValidData(): void
    {
        $orderPath = 'pages/order/detail?id=123';
        $this->returnOrder->setOrderPath($orderPath);
        $this->assertEquals($orderPath, $this->returnOrder->getOrderPath());
    }
    
    public function testOrderPath_withNull(): void
    {
        $this->returnOrder->setOrderPath(null);
        $this->assertNull($this->returnOrder->getOrderPath());
    }
    
    public function testGoodsList_withValidArray(): void
    {
        $goodsList = [
            ['id' => 1, 'name' => '商品1', 'price' => 100],
            ['id' => 2, 'name' => '商品2', 'price' => 200],
        ];
        $this->returnOrder->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $this->returnOrder->getGoodsList());
    }
    
    public function testGoodsList_withNull(): void
    {
        $this->returnOrder->setGoodsList(null);
        $this->assertNull($this->returnOrder->getGoodsList());
    }
    
    public function testWxPayId_withValidData(): void
    {
        $wxPayId = '4200000001202107010123456789';
        $this->returnOrder->setWxPayId($wxPayId);
        $this->assertEquals($wxPayId, $this->returnOrder->getWxPayId());
    }
} 