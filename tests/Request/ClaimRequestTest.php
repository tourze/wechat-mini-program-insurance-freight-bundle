<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\ClaimRequest;

/**
 * @internal
 */
#[CoversClass(ClaimRequest::class)]
final class ClaimRequestTest extends RequestTestCase
{
    private ClaimRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new ClaimRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('/wxa/business/insurance_freight/claim', $this->request->getRequestPath());
    }

    public function testGetRequestOptionsWithValidData(): void
    {
        $account = new Account();

        $this->request->setAccount($account);
        $this->request->setOpenid('o1234567890abcdef');
        $this->request->setOrderNo('2021123456789');
        $this->request->setRefundDeliveryNo('SF0987654321');
        $this->request->setRefundCompany('顺丰速运');

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
        $this->assertEquals('SF0987654321', $json['refund_delivery_no']);
        $this->assertEquals('顺丰速运', $json['refund_company']);
    }

    public function testGettersAndSetters(): void
    {
        $account = new Account();
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());

        $openid = 'o1234567890abcdef';
        $this->request->setOpenid($openid);
        $this->assertEquals($openid, $this->request->getOpenid());

        // 测试 OrderNo
        $orderNo = '2021123456789';
        $this->request->setOrderNo($orderNo);
        $this->assertEquals($orderNo, $this->request->getOrderNo());

        // 测试 RefundDeliveryNo
        $refundDeliveryNo = 'SF0987654321';
        $this->request->setRefundDeliveryNo($refundDeliveryNo);
        $this->assertEquals($refundDeliveryNo, $this->request->getRefundDeliveryNo());

        // 测试 RefundCompany
        $refundCompany = '顺丰速运';
        $this->request->setRefundCompany($refundCompany);
        $this->assertEquals($refundCompany, $this->request->getRefundCompany());
    }
}
