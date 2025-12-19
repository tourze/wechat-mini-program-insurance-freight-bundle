<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\CreateInsuranceOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\Goods;
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
        $account = new Account();

        $deliveryPlace = new Place();
        $deliveryPlace->setProvince('广东省');
        $deliveryPlace->setCity('深圳市');
        $deliveryPlace->setCounty('南山区');
        $deliveryPlace->setAddress('深圳湾科技生态园');

        $receiptPlace = new Place();
        $receiptPlace->setProvince('北京市');
        $receiptPlace->setCity('北京市');
        $receiptPlace->setCounty('朝阳区');
        $receiptPlace->setAddress('朝阳公园路');

        $goods = new Goods();
        $goods->setName('测试商品');
        $goods->setUrl('https://example.com/goods.jpg');

        $productInfo = new ProductInfo();
        $productInfo->setOrderPath('pages/order/detail');
        $productInfo->addGoods($goods);

        $deliveryPlaceArray = $deliveryPlace->retrievePlainArray();
        $receiptPlaceArray = $receiptPlace->retrievePlainArray();
        $productInfoArray = $productInfo->retrievePlainArray();

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
        $account = new Account();
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());

        $openId = 'o1234567890abcdef';
        $this->request->setOpenId($openId);
        $this->assertEquals($openId, $this->request->getOpenId());

        $orderNo = '2021123456789';
        $this->request->setOrderNo($orderNo);
        $this->assertEquals($orderNo, $this->request->getOrderNo());

        $payTime = 1625097600; // 2021-07-01 00:00:00
        $this->request->setPayTime($payTime);
        $this->assertEquals($payTime, $this->request->getPayTime());

        $payAmount = 1000;
        $this->request->setPayAmount($payAmount);
        $this->assertEquals($payAmount, $this->request->getPayAmount());

        $deliveryNo = 'SF1234567890';
        $this->request->setDeliveryNo($deliveryNo);
        $this->assertEquals($deliveryNo, $this->request->getDeliveryNo());

        $deliveryPlace = new Place();
        $this->request->setDeliveryPlace($deliveryPlace);
        $this->assertSame($deliveryPlace, $this->request->getDeliveryPlace());

        $receiptPlace = new Place();
        $this->request->setReceiptPlace($receiptPlace);
        $this->assertSame($receiptPlace, $this->request->getReceiptPlace());

        $productInfo = new ProductInfo();
        $this->request->setProductInfo($productInfo);
        $this->assertSame($productInfo, $this->request->getProductInfo());
    }
}
