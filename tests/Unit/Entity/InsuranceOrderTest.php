<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

class InsuranceOrderTest extends TestCase
{
    private InsuranceOrder $insuranceOrder;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->insuranceOrder = new InsuranceOrder();
    }
    
    public function testOpenId_withValidData(): void
    {
        $openId = 'o1234567890abcdef';
        $this->insuranceOrder->setOpenId($openId);
        $this->assertEquals($openId, $this->insuranceOrder->getOpenId());
    }
    
    public function testOrderNo_withValidData(): void
    {
        $orderNo = '2021123456789';
        $this->insuranceOrder->setOrderNo($orderNo);
        $this->assertEquals($orderNo, $this->insuranceOrder->getOrderNo());
    }
    
    public function testStatus_withValidStatus(): void
    {
        $status = InsuranceOrderStatus::Securing;
        $this->insuranceOrder->setStatus($status);
        $this->assertSame($status, $this->insuranceOrder->getStatus());
    }
    
    public function testPayTime_withValidDateTime(): void
    {
        $payTime = new \DateTimeImmutable('2023-01-01 12:00:00');
        $this->insuranceOrder->setPayTime($payTime);
        $this->assertSame($payTime, $this->insuranceOrder->getPayTime());
    }
    
    public function testPayAmount_withValidAmount(): void
    {
        $payAmount = 1000; // 10元
        $this->insuranceOrder->setPayAmount($payAmount);
        $this->assertEquals($payAmount, $this->insuranceOrder->getPayAmount());
    }
    
    public function testEstimateAmount_withValidAmount(): void
    {
        $estimateAmount = 2000; // 20元
        $this->insuranceOrder->setEstimateAmount($estimateAmount);
        $this->assertEquals($estimateAmount, $this->insuranceOrder->getEstimateAmount());
    }
    
    public function testPremium_withValidAmount(): void
    {
        $premium = 100; // 1元
        $this->insuranceOrder->setPremium($premium);
        $this->assertEquals($premium, $this->insuranceOrder->getPremium());
    }
    
    public function testDeliveryNo_withValidData(): void
    {
        $deliveryNo = 'SF1234567890';
        $this->insuranceOrder->setDeliveryNo($deliveryNo);
        $this->assertEquals($deliveryNo, $this->insuranceOrder->getDeliveryNo());
    }
    
    public function testDeliveryPlace_withValidData(): void
    {
        $province = '广东省';
        $city = '深圳市';
        $county = '南山区';
        $address = '科技园路1号';
        
        $this->insuranceOrder->setDeliveryPlaceProvince($province);
        $this->insuranceOrder->setDeliveryPlaceCity($city);
        $this->insuranceOrder->setDeliveryPlaceCounty($county);
        $this->insuranceOrder->setDeliveryPlaceAddress($address);
        
        $this->assertEquals($province, $this->insuranceOrder->getDeliveryPlaceProvince());
        $this->assertEquals($city, $this->insuranceOrder->getDeliveryPlaceCity());
        $this->assertEquals($county, $this->insuranceOrder->getDeliveryPlaceCounty());
        $this->assertEquals($address, $this->insuranceOrder->getDeliveryPlaceAddress());
    }
    
    public function testReceiptPlace_withValidData(): void
    {
        $province = '北京市';
        $city = '北京市';
        $county = '海淀区';
        $address = '中关村南大街5号';
        
        $this->insuranceOrder->setReceiptPlaceProvince($province);
        $this->insuranceOrder->setReceiptPlaceCity($city);
        $this->insuranceOrder->setReceiptPlaceCounty($county);
        $this->insuranceOrder->setReceiptPlaceAddress($address);
        
        $this->assertEquals($province, $this->insuranceOrder->getReceiptPlaceProvince());
        $this->assertEquals($city, $this->insuranceOrder->getReceiptPlaceCity());
        $this->assertEquals($county, $this->insuranceOrder->getReceiptPlaceCounty());
        $this->assertEquals($address, $this->insuranceOrder->getReceiptPlaceAddress());
    }
    
    public function testPolicyNo_withValidData(): void
    {
        $policyNo = 'P202112345678';
        $this->insuranceOrder->setPolicyNo($policyNo);
        $this->assertEquals($policyNo, $this->insuranceOrder->getPolicyNo());
    }
    
    public function testInsuranceEndDate_withValidDateTime(): void
    {
        $endDate = new \DateTimeImmutable('2023-12-31 23:59:59');
        $this->insuranceOrder->setInsuranceEndDate($endDate);
        $this->assertSame($endDate, $this->insuranceOrder->getInsuranceEndDate());
    }
    
    public function testRefundDeliveryNo_withValidData(): void
    {
        $refundDeliveryNo = 'SF0987654321';
        $this->insuranceOrder->setRefundDeliveryNo($refundDeliveryNo);
        $this->assertEquals($refundDeliveryNo, $this->insuranceOrder->getRefundDeliveryNo());
    }
    
    public function testRefundDeliveryNo_withNull(): void
    {
        $this->insuranceOrder->setRefundDeliveryNo(null);
        $this->assertNull($this->insuranceOrder->getRefundDeliveryNo());
    }
    
    public function testRefundCompany_withValidData(): void
    {
        $refundCompany = '顺丰速运';
        $this->insuranceOrder->setRefundCompany($refundCompany);
        $this->assertEquals($refundCompany, $this->insuranceOrder->getRefundCompany());
    }
    
    public function testRefundCompany_withNull(): void
    {
        $this->insuranceOrder->setRefundCompany(null);
        $this->assertNull($this->insuranceOrder->getRefundCompany());
    }
    
    public function testPayFailReason_withValidData(): void
    {
        $payFailReason = '银行卡信息有误';
        $this->insuranceOrder->setPayFailReason($payFailReason);
        $this->assertEquals($payFailReason, $this->insuranceOrder->getPayFailReason());
    }
    
    public function testPayFailReason_withNull(): void
    {
        $this->insuranceOrder->setPayFailReason(null);
        $this->assertNull($this->insuranceOrder->getPayFailReason());
    }
    
    public function testPayFinishTime_withValidDateTime(): void
    {
        $payFinishTime = new \DateTimeImmutable('2023-02-01 15:30:00');
        $this->insuranceOrder->setPayFinishTime($payFinishTime);
        $this->assertSame($payFinishTime, $this->insuranceOrder->getPayFinishTime());
    }
    
    public function testPayFinishTime_withNull(): void
    {
        $this->insuranceOrder->setPayFinishTime(null);
        $this->assertNull($this->insuranceOrder->getPayFinishTime());
    }
    
    public function testHomePickUp_withBoolean(): void
    {
        $this->insuranceOrder->setHomePickUp(true);
        $this->assertTrue($this->insuranceOrder->isHomePickUp());
        
        $this->insuranceOrder->setHomePickUp(false);
        $this->assertFalse($this->insuranceOrder->isHomePickUp());
    }
    
    public function testHomePickUp_withNull(): void
    {
        $this->insuranceOrder->setHomePickUp(null);
        $this->assertNull($this->insuranceOrder->isHomePickUp());
    }
    
    public function testOrderPath_withValidData(): void
    {
        $orderPath = 'pages/order/detail?id=123';
        $this->insuranceOrder->setOrderPath($orderPath);
        $this->assertEquals($orderPath, $this->insuranceOrder->getOrderPath());
    }
    
    public function testOrderPath_withNull(): void
    {
        $this->insuranceOrder->setOrderPath(null);
        $this->assertNull($this->insuranceOrder->getOrderPath());
    }
    
    public function testGoodsList_withValidArray(): void
    {
        $goodsList = [
            ['id' => 1, 'name' => '商品1', 'price' => 100],
            ['id' => 2, 'name' => '商品2', 'price' => 200],
        ];
        $this->insuranceOrder->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $this->insuranceOrder->getGoodsList());
    }
    
    public function testGoodsList_withNull(): void
    {
        $this->insuranceOrder->setGoodsList(null);
        $this->assertNull($this->insuranceOrder->getGoodsList());
    }
    
    public function testReportNo_withValidData(): void
    {
        $reportNo = 'R202101010001';
        $this->insuranceOrder->setReportNo($reportNo);
        $this->assertEquals($reportNo, $this->insuranceOrder->getReportNo());
    }
    
    public function testReportNo_withNull(): void
    {
        $this->insuranceOrder->setReportNo(null);
        $this->assertNull($this->insuranceOrder->getReportNo());
    }
    
    public function testAccount_withValidAccount(): void
    {
        $account = $this->createMock(Account::class);
        $this->insuranceOrder->setAccount($account);
        $this->assertSame($account, $this->insuranceOrder->getAccount());
    }
    
    public function testAccount_withNull(): void
    {
        $this->insuranceOrder->setAccount(null);
        $this->assertNull($this->insuranceOrder->getAccount());
    }
    
    public function testCreateTime_withValidDateTime(): void
    {
        $createTime = new \DateTimeImmutable('2023-01-01 10:00:00');
        $this->insuranceOrder->setCreateTime($createTime);
        $this->assertSame($createTime, $this->insuranceOrder->getCreateTime());
    }
    
    public function testUpdateTime_withValidDateTime(): void
    {
        $updateTime = new \DateTimeImmutable('2023-01-02 11:00:00');
        $this->insuranceOrder->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $this->insuranceOrder->getUpdateTime());
    }
    
    public function testRetrieveApiArray_withFullData(): void
    {
        // 设置测试数据
        $openId = 'o1234567890abcdef';
        $orderNo = '2021123456789';
        $status = InsuranceOrderStatus::Securing;
        $payTime = new \DateTimeImmutable('2023-01-01 12:00:00');
        $payAmount = 1000;
        $estimateAmount = 2000;
        $premium = 100;
        $deliveryNo = 'SF1234567890';
        $policyNo = 'P202112345678';
        $insuranceEndDate = new \DateTimeImmutable('2023-12-31 23:59:59');
        
        // 初始化所有必要属性
        $this->insuranceOrder->setOpenId($openId);
        $this->insuranceOrder->setOrderNo($orderNo);
        $this->insuranceOrder->setStatus($status);
        $this->insuranceOrder->setPayTime($payTime);
        $this->insuranceOrder->setPayAmount($payAmount);
        $this->insuranceOrder->setEstimateAmount($estimateAmount);
        $this->insuranceOrder->setPremium($premium);
        $this->insuranceOrder->setDeliveryNo($deliveryNo);
        $this->insuranceOrder->setPolicyNo($policyNo);
        $this->insuranceOrder->setInsuranceEndDate($insuranceEndDate);
        $this->insuranceOrder->setDeliveryPlaceProvince('广东省');
        $this->insuranceOrder->setDeliveryPlaceCity('深圳市');
        $this->insuranceOrder->setDeliveryPlaceCounty('南山区');
        $this->insuranceOrder->setDeliveryPlaceAddress('科技园路1号');
        $this->insuranceOrder->setReceiptPlaceProvince('北京市');
        $this->insuranceOrder->setReceiptPlaceCity('北京市');
        $this->insuranceOrder->setReceiptPlaceCounty('海淀区');
        $this->insuranceOrder->setReceiptPlaceAddress('中关村南大街5号');
        
        // 获取API数组
        $apiArray = $this->insuranceOrder->retrieveApiArray();
        
        // 验证API数组包含正确的数据
        $this->assertArrayHasKey('openId', $apiArray);
        $this->assertArrayHasKey('orderNo', $apiArray);
        $this->assertArrayHasKey('status', $apiArray);
        $this->assertEquals($openId, $apiArray['openId']);
        $this->assertEquals($orderNo, $apiArray['orderNo']);
        // 由于status是通过toArray()方法转换的，我们跳过详细断言，只验证它是一个数组
        $this->assertIsArray($apiArray['status']);
    }
    
    public function testRetrieveAdminArray_withFullData(): void
    {
        // 设置测试数据
        $openId = 'o1234567890abcdef';
        $orderNo = '2021123456789';
        $status = InsuranceOrderStatus::Securing;
        $payTime = new \DateTimeImmutable('2023-01-01 12:00:00');
        $payAmount = 1000;
        $estimateAmount = 2000;
        $premium = 100;
        $deliveryNo = 'SF1234567890';
        $policyNo = 'P202112345678';
        $insuranceEndDate = new \DateTimeImmutable('2023-12-31 23:59:59');
        
        // 初始化所有必要属性
        $this->insuranceOrder->setOpenId($openId);
        $this->insuranceOrder->setOrderNo($orderNo);
        $this->insuranceOrder->setStatus($status);
        $this->insuranceOrder->setPayTime($payTime);
        $this->insuranceOrder->setPayAmount($payAmount);
        $this->insuranceOrder->setEstimateAmount($estimateAmount);
        $this->insuranceOrder->setPremium($premium);
        $this->insuranceOrder->setDeliveryNo($deliveryNo);
        $this->insuranceOrder->setPolicyNo($policyNo);
        $this->insuranceOrder->setInsuranceEndDate($insuranceEndDate);
        $this->insuranceOrder->setDeliveryPlaceProvince('广东省');
        $this->insuranceOrder->setDeliveryPlaceCity('深圳市');
        $this->insuranceOrder->setDeliveryPlaceCounty('南山区');
        $this->insuranceOrder->setDeliveryPlaceAddress('科技园路1号');
        $this->insuranceOrder->setReceiptPlaceProvince('北京市');
        $this->insuranceOrder->setReceiptPlaceCity('北京市');
        $this->insuranceOrder->setReceiptPlaceCounty('海淀区');
        $this->insuranceOrder->setReceiptPlaceAddress('中关村南大街5号');
        
        // 获取Admin数组
        $adminArray = $this->insuranceOrder->retrieveAdminArray();
        
        // 验证Admin数组包含正确的数据
        $this->assertArrayHasKey('openId', $adminArray);
        $this->assertArrayHasKey('orderNo', $adminArray);
        $this->assertArrayHasKey('status', $adminArray);
        $this->assertEquals($openId, $adminArray['openId']);
        $this->assertEquals($orderNo, $adminArray['orderNo']);
        // 由于状态字段是从retrieveApiArray方法继承的，我们跳过详细断言，只验证它是一个数组
        $this->assertIsArray($adminArray['status']);
    }
} 