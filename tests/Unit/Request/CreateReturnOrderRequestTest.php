<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\Address;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateReturnOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\Goods;

class CreateReturnOrderRequestTest extends TestCase
{
    private CreateReturnOrderRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new CreateReturnOrderRequest();
    }
    
    public function testGetRequestPath(): void
    {
        $this->assertEquals('/cgi-bin/express/delivery/no_worry_return/add', $this->request->getRequestPath());
    }
    
    public function testGetRequestOptions_withValidData(): void
    {
        // 准备测试数据
        $account = $this->createMock(Account::class);
        $bizAddress = $this->createMock(Address::class);
        $userAddress = $this->createMock(Address::class);
        $goods = $this->createMock(Goods::class);
        
        $bizAddressArray = ['province' => '广东省', 'city' => '深圳市'];
        $userAddressArray = ['province' => '北京市', 'city' => '北京市'];
        $goodsArray = ['name' => '测试商品', 'price' => 100];
        
        $bizAddress->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($bizAddressArray);
        
        $userAddress->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($userAddressArray);
        
        $goods->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($goodsArray);
        
        // 设置请求参数
        $this->request->setAccount($account);
        $this->request->setShopOrderId('SHOP123456');
        $this->request->setBizAddress($bizAddress);
        $this->request->setUserAddress($userAddress);
        $this->request->setOpenId('o1234567890abcdef');
        $this->request->setWxPayId('2021123456789');
        $this->request->setOrderPath('pages/order/detail?id=123');
        $this->request->setGoodsList([$goods]);
        
        // 获取请求选项
        $options = $this->request->getRequestOptions();
        
        // 验证结果
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        
        $json = $options['json'];
        $this->assertEquals('SHOP123456', $json['shop_order_id']);
        $this->assertEquals($bizAddressArray, $json['biz_addr']);
        $this->assertEquals($userAddressArray, $json['user_addr']);
        $this->assertEquals('o1234567890abcdef', $json['openid']);
        $this->assertEquals('2021123456789', $json['wx_pay_id']);
        $this->assertEquals('pages/order/detail?id=123', $json['order_path']);
        $this->assertEquals([$goodsArray], $json['goods_list']);
    }
    
    public function testGettersAndSetters(): void
    {
        // 测试 Account
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
        
        // 测试 ShopOrderId
        $shopOrderId = 'SHOP123456';
        $this->request->setShopOrderId($shopOrderId);
        $this->assertEquals($shopOrderId, $this->request->getShopOrderId());
        
        // 测试 BizAddress
        $bizAddress = $this->createMock(Address::class);
        $this->request->setBizAddress($bizAddress);
        $this->assertSame($bizAddress, $this->request->getBizAddress());
        
        // 测试 UserAddress
        $userAddress = $this->createMock(Address::class);
        $this->request->setUserAddress($userAddress);
        $this->assertSame($userAddress, $this->request->getUserAddress());
        
        // 测试 OpenId
        $openId = 'o1234567890abcdef';
        $this->request->setOpenId($openId);
        $this->assertEquals($openId, $this->request->getOpenId());
        
        // 测试 WxPayId
        $wxPayId = '2021123456789';
        $this->request->setWxPayId($wxPayId);
        $this->assertEquals($wxPayId, $this->request->getWxPayId());
        
        // 测试 OrderPath
        $orderPath = 'pages/order/detail?id=123';
        $this->request->setOrderPath($orderPath);
        $this->assertEquals($orderPath, $this->request->getOrderPath());
        
        // 测试 GoodsList
        $goods1 = $this->createMock(Goods::class);
        $goods2 = $this->createMock(Goods::class);
        $goodsList = [$goods1, $goods2];
        $this->request->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $this->request->getGoodsList());
    }
    
    public function testAddGoods(): void
    {
        $goods1 = $this->createMock(Goods::class);
        $goods2 = $this->createMock(Goods::class);
        
        $this->request->setGoodsList([$goods1]);
        $this->request->addGoods($goods2);
        
        $goodsList = $this->request->getGoodsList();
        $this->assertCount(2, $goodsList);
        $this->assertSame($goods1, $goodsList[0]);
        $this->assertSame($goods2, $goodsList[1]);
    }
    
    public function testGetRequestOptions_withArrayGoods(): void
    {
        // 测试处理非Goods对象的数组元素
        $account = $this->createMock(Account::class);
        $bizAddress = $this->createMock(Address::class);
        $userAddress = $this->createMock(Address::class);
        
        $bizAddress->method('retrievePlainArray')->willReturn([]);
        $userAddress->method('retrievePlainArray')->willReturn([]);
        
        $this->request->setAccount($account);
        $this->request->setShopOrderId('SHOP123456');
        $this->request->setBizAddress($bizAddress);
        $this->request->setUserAddress($userAddress);
        $this->request->setOpenId('o1234567890abcdef');
        $this->request->setWxPayId('2021123456789');
        $this->request->setOrderPath('pages/order/detail?id=123');
        $this->request->setGoodsList([['name' => '商品1', 'price' => 100]]);
        
        $options = $this->request->getRequestOptions();
        $json = $options['json'];
        
        $this->assertEquals([['name' => '商品1', 'price' => 100]], $json['goods_list']);
    }
}