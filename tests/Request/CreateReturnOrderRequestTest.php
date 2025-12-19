<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\Address;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateReturnOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\Goods;

/**
 * @internal
 */
#[CoversClass(CreateReturnOrderRequest::class)]
final class CreateReturnOrderRequestTest extends RequestTestCase
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

    public function testGetRequestOptionsWithValidData(): void
    {
        $account = new Account();

        $bizAddress = new Address();
        $bizAddress->setName('商家名');
        $bizAddress->setMobile('13800138000');
        $bizAddress->setCountry('中国');
        $bizAddress->setProvince('广东省');
        $bizAddress->setCity('深圳市');
        $bizAddress->setArea('南山区');
        $bizAddress->setAddress('科技园');

        $userAddress = new Address();
        $userAddress->setName('用户名');
        $userAddress->setMobile('13900139000');
        $userAddress->setCountry('中国');
        $userAddress->setProvince('北京市');
        $userAddress->setCity('北京市');
        $userAddress->setArea('朝阳区');
        $userAddress->setAddress('三里屯');

        $goods = new Goods();
        $goods->setName('测试商品');
        $goods->setUrl('https://example.com/goods.jpg');

        $bizAddressArray = $bizAddress->retrievePlainArray();
        $userAddressArray = $userAddress->retrievePlainArray();
        $goodsArray = $goods->retrievePlainArray();

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
        $this->assertIsArray($options['json']);

        /** @var array<string, mixed> $json */
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
        $account = new Account();
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());

        $shopOrderId = 'SHOP123456';
        $this->request->setShopOrderId($shopOrderId);
        $this->assertEquals($shopOrderId, $this->request->getShopOrderId());

        $bizAddress = new Address();
        $this->request->setBizAddress($bizAddress);
        $this->assertSame($bizAddress, $this->request->getBizAddress());

        $userAddress = new Address();
        $this->request->setUserAddress($userAddress);
        $this->assertSame($userAddress, $this->request->getUserAddress());

        $openId = 'o1234567890abcdef';
        $this->request->setOpenId($openId);
        $this->assertEquals($openId, $this->request->getOpenId());

        $wxPayId = '2021123456789';
        $this->request->setWxPayId($wxPayId);
        $this->assertEquals($wxPayId, $this->request->getWxPayId());

        $orderPath = 'pages/order/detail?id=123';
        $this->request->setOrderPath($orderPath);
        $this->assertEquals($orderPath, $this->request->getOrderPath());

        $goods1 = new Goods();
        $goods2 = new Goods();
        $goodsList = [$goods1, $goods2];
        $this->request->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $this->request->getGoodsList());
    }

    public function testAddGoods(): void
    {
        $goods1 = new Goods();
        $goods2 = new Goods();

        $this->request->setGoodsList([$goods1]);
        $this->request->addGoods($goods2);

        $goodsList = $this->request->getGoodsList();
        $this->assertCount(2, $goodsList);
        $this->assertSame($goods1, $goodsList[0]);
        $this->assertSame($goods2, $goodsList[1]);
    }

    public function testGetRequestOptionsWithArrayGoods(): void
    {
        $account = new Account();

        $bizAddress = new Address();
        $bizAddress->setName('商家名');
        $bizAddress->setMobile('13800138000');
        $bizAddress->setCountry('中国');
        $bizAddress->setProvince('广东省');
        $bizAddress->setCity('深圳市');
        $bizAddress->setArea('南山区');
        $bizAddress->setAddress('科技园');

        $userAddress = new Address();
        $userAddress->setName('用户名');
        $userAddress->setMobile('13900139000');
        $userAddress->setCountry('中国');
        $userAddress->setProvince('北京市');
        $userAddress->setCity('北京市');
        $userAddress->setArea('朝阳区');
        $userAddress->setAddress('三里屯');

        $this->request->setAccount($account);
        $this->request->setShopOrderId('SHOP123456');
        $this->request->setBizAddress($bizAddress);
        $this->request->setUserAddress($userAddress);
        $this->request->setOpenId('o1234567890abcdef');
        $this->request->setWxPayId('2021123456789');
        $this->request->setOrderPath('pages/order/detail?id=123');
        $this->request->setGoodsList([['name' => '商品1', 'price' => 100]]);

        $options = $this->request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertArrayHasKey('goods_list', $json);
        $this->assertEquals([['name' => '商品1', 'price' => 100]], $json['goods_list']);
    }
}
