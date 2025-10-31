<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateInsuranceOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\Place;
use WechatMiniProgramInsuranceFreightBundle\Request\ProductInfo;

/**
 * @internal
 */
#[CoversClass(CreateInsuranceOrderRequest::class)]
final class CreateInsuranceOrderRequestTest extends RequestTestCase
{
    private CreateInsuranceOrderRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateInsuranceOrderRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('/wxa/business/insurance_freight/createorder', $this->request->getRequestPath());
    }

    public function testGetRequestOptionsWithValidData(): void
    {
        // 准备测试数据
        // Mock具体类说明: WechatMiniProgramBundle\Entity\Account是数据实体类，
        // 没有对应的接口定义，测试中需要模拟其行为来验证业务逻辑。
        // 使用具体类Mock是合理的，因为Entity类主要包含数据属性和简单的getter/setter方法。
        // 替代方案：可以考虑创建测试专用的Entity工厂类，但当前Mock方式更直观简洁。
        $account = $this->createMock(Account::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Place是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证数据转换逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Place对象，但Mock方式可以提供更可控的测试数据。
        $deliveryPlace = $this->createMock(Place::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Place是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证数据转换逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Place对象，但Mock方式可以提供更可控的测试数据。
        $receiptPlace = $this->createMock(Place::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\ProductInfo是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证产品信息转换逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的ProductInfo对象，但Mock方式可以提供更可控的测试数据。
        $productInfo = $this->createMock(ProductInfo::class);

        $deliveryPlaceArray = ['province' => '广东省', 'city' => '深圳市'];
        $receiptPlaceArray = ['province' => '北京市', 'city' => '北京市'];
        $productInfoArray = ['path' => 'pages/order/detail', 'name' => '测试商品'];

        $deliveryPlace->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($deliveryPlaceArray)
        ;

        $receiptPlace->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($receiptPlaceArray)
        ;

        $productInfo->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($productInfoArray)
        ;

        // 设置请求参数
        $this->request->setAccount($account);
        $this->request->setOpenId('o1234567890abcdef');
        $this->request->setOrderNo('2021123456789');
        $this->request->setPayTime(1625097600); // 2021-07-01 00:00:00
        $this->request->setPayAmount(1000);
        $this->request->setDeliveryNo('SF1234567890');
        $this->request->setDeliveryPlace($deliveryPlace);
        $this->request->setReceiptPlace($receiptPlace);
        $this->request->setProductInfo($productInfo);

        // 获取请求选项
        $options = $this->request->getRequestOptions();

        // 验证结果
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertEquals('o1234567890abcdef', $json['openid']);
        $this->assertEquals('2021123456789', $json['order_no']);
        $this->assertEquals(1625097600, $json['pay_time']);
        $this->assertEquals(1000, $json['pay_amount']);
        $this->assertEquals('SF1234567890', $json['delivery_no']);
        $this->assertEquals($deliveryPlaceArray, $json['delivery_place']);
        $this->assertEquals($receiptPlaceArray, $json['receipt_place']);
        $this->assertEquals($productInfoArray, $json['product_info']);
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

        // 测试 OpenId
        $openId = 'o1234567890abcdef';
        $this->request->setOpenId($openId);
        $this->assertEquals($openId, $this->request->getOpenId());

        // 测试 OrderNo
        $orderNo = '2021123456789';
        $this->request->setOrderNo($orderNo);
        $this->assertEquals($orderNo, $this->request->getOrderNo());

        // 测试 PayTime
        $payTime = 1625097600; // 2021-07-01 00:00:00
        $this->request->setPayTime($payTime);
        $this->assertEquals($payTime, $this->request->getPayTime());

        // 测试 PayAmount
        $payAmount = 1000;
        $this->request->setPayAmount($payAmount);
        $this->assertEquals($payAmount, $this->request->getPayAmount());

        // 测试 DeliveryNo
        $deliveryNo = 'SF1234567890';
        $this->request->setDeliveryNo($deliveryNo);
        $this->assertEquals($deliveryNo, $this->request->getDeliveryNo());

        // 测试 DeliveryPlace
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Place是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证setter/getter方法。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Place对象，但Mock方式更简洁且可控。
        $deliveryPlace = $this->createMock(Place::class);
        $this->request->setDeliveryPlace($deliveryPlace);
        $this->assertSame($deliveryPlace, $this->request->getDeliveryPlace());

        // 测试 ReceiptPlace
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Place是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证setter/getter方法。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Place对象，但Mock方式更简洁且可控。
        $receiptPlace = $this->createMock(Place::class);
        $this->request->setReceiptPlace($receiptPlace);
        $this->assertSame($receiptPlace, $this->request->getReceiptPlace());

        // 测试 ProductInfo
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\ProductInfo是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证setter/getter方法。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的ProductInfo对象，但Mock方式更简洁且可控。
        $productInfo = $this->createMock(ProductInfo::class);
        $this->request->setProductInfo($productInfo);
        $this->assertSame($productInfo, $this->request->getProductInfo());
    }
}
