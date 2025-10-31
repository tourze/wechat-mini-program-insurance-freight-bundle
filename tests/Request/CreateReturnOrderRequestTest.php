<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
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
        // 准备测试数据
        // Mock具体类说明: WechatMiniProgramBundle\Entity\Account是数据实体类，
        // 没有对应的接口定义，测试中需要模拟其行为来验证业务逻辑。
        // 使用具体类Mock是合理的，因为Entity类主要包含数据属性和简单的getter/setter方法。
        // 替代方案：可以考虑创建测试专用的Entity工厂类，但当前Mock方式更直观简洁。
        $account = $this->createMock(Account::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Address是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证地址数据转换逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Address对象，但Mock方式可以提供更可控的测试数据。
        $bizAddress = $this->createMock(Address::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Address是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证用户地址数据转换逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Address对象，但Mock方式可以提供更可控的测试数据。
        $userAddress = $this->createMock(Address::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证商品数据转换逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式可以提供更可控的测试数据。
        $goods = $this->createMock(Goods::class);

        $bizAddressArray = ['province' => '广东省', 'city' => '深圳市'];
        $userAddressArray = ['province' => '北京市', 'city' => '北京市'];
        $goodsArray = ['name' => '测试商品', 'price' => 100];

        $bizAddress->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($bizAddressArray)
        ;

        $userAddress->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($userAddressArray)
        ;

        $goods->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($goodsArray)
        ;

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
        // 测试 Account
        // Mock具体类说明: WechatMiniProgramBundle\Entity\Account是数据实体类，
        // 没有对应的接口定义，测试中需要模拟其行为来验证业务逻辑。
        // 使用具体类Mock是合理的，因为Entity类主要包含数据属性和简单的getter/setter方法。
        // 替代方案：可以考虑创建测试专用的Entity工厂类，但当前Mock方式更直观简洁。
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());

        // 测试 ShopOrderId
        $shopOrderId = 'SHOP123456';
        $this->request->setShopOrderId($shopOrderId);
        $this->assertEquals($shopOrderId, $this->request->getShopOrderId());

        // 测试 BizAddress
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Address是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证setter/getter方法。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Address对象，但Mock方式更简洁且可控。
        $bizAddress = $this->createMock(Address::class);
        $this->request->setBizAddress($bizAddress);
        $this->assertSame($bizAddress, $this->request->getBizAddress());

        // 测试 UserAddress
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Address是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证setter/getter方法。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Address对象，但Mock方式更简洁且可控。
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
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证商品列表操作逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式更简洁且可控。
        $goods1 = $this->createMock(Goods::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证商品列表操作逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式更简洁且可控。
        $goods2 = $this->createMock(Goods::class);
        $goodsList = [$goods1, $goods2];
        $this->request->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $this->request->getGoodsList());
    }

    public function testAddGoods(): void
    {
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证addGoods方法的数组追加逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式更简洁且可控。
        $goods1 = $this->createMock(Goods::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证addGoods方法追加第二个商品的逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式更简洁且可控。
        $goods2 = $this->createMock(Goods::class);

        $this->request->setGoodsList([$goods1]);
        $this->request->addGoods($goods2);

        $goodsList = $this->request->getGoodsList();
        $this->assertCount(2, $goodsList);
        $this->assertSame($goods1, $goodsList[0]);
        $this->assertSame($goods2, $goodsList[1]);
    }

    public function testGetRequestOptionsWithArrayGoods(): void
    {
        // 测试处理非Goods对象的数组元素
        // Mock具体类说明: WechatMiniProgramBundle\Entity\Account是数据实体类，
        // 没有对应的接口定义，测试中需要模拟其行为来验证业务逻辑。
        // 使用具体类Mock是合理的，因为Entity类主要包含数据属性和简单的getter/setter方法。
        // 替代方案：可以考虑创建测试专用的Entity工厂类，但当前Mock方式更直观简洁。
        $account = $this->createMock(Account::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Address是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证商家地址数据处理逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Address对象，但Mock方式可以提供更可控的测试数据。
        $bizAddress = $this->createMock(Address::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Address是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证用户地址数据处理逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Address对象，但Mock方式可以提供更可控的测试数据。
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
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertArrayHasKey('goods_list', $json);
        $this->assertEquals([['name' => '商品1', 'price' => 100]], $json['goods_list']);
    }
}
