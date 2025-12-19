<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use Carbon\CarbonImmutable;
use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Enum\SortDirect;
use WechatMiniProgramInsuranceFreightBundle\Request\GetInsuranceOrderListRequest;

/**
 * @internal
 */
#[CoversClass(GetInsuranceOrderListRequest::class)]
final class GetInsuranceOrderListRequestTest extends RequestTestCase
{
    private GetInsuranceOrderListRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetInsuranceOrderListRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('/wxa/business/insurance_freight/getorderlist', $this->request->getRequestPath());
    }

    public function testGetRequestOptionsWithMinimalData(): void
    {
        $account = new Account();

        $this->request->setAccount($account);
        $this->request->setLimit(10);
        $this->request->setOffset(0);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertEquals(10, $json['limit']);
        $this->assertEquals(0, $json['offset']);
    }

    public function testGetRequestOptionsWithFullData(): void
    {
        $account = new Account();
        $beginTime = CarbonImmutable::createFromTimestamp(1625097600); // 2021-07-01 00:00:00
        $endTime = CarbonImmutable::createFromTimestamp(1627689600); // 2021-07-31 00:00:00

        $this->request->setAccount($account);
        $this->request->setLimit(10);
        $this->request->setOffset(0);
        $this->request->setOpenId('o1234567890abcdef');
        $this->request->setOrderNo('2021123456789');
        $this->request->setDeliveryNo('SF1234567890');
        $this->request->setBeginTime($beginTime);
        $this->request->setEndTime($endTime);
        $this->request->setSortDirect(SortDirect::DESC);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertEquals(10, $json['limit']);
        $this->assertEquals(0, $json['offset']);
        $this->assertEquals('o1234567890abcdef', $json['openid']);
        $this->assertEquals('2021123456789', $json['order_no']);
        $this->assertEquals('SF1234567890', $json['delivery_no']);
        $this->assertEquals(1625097600, $json['begin_time']);
        $this->assertEquals(1627689600, $json['end_time']);
        $this->assertEquals(SortDirect::DESC->value, $json['sort_direct']);
    }

    public function testGettersAndSetters(): void
    {
        $account = new Account();
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());

        $limit = 10;
        $this->request->setLimit($limit);
        $this->assertEquals($limit, $this->request->getLimit());

        // 测试 Offset
        $offset = 20;
        $this->request->setOffset($offset);
        $this->assertEquals($offset, $this->request->getOffset());

        // 测试 OpenId
        $openId = 'o1234567890abcdef';
        $this->request->setOpenId($openId);
        $this->assertEquals($openId, $this->request->getOpenId());

        // 测试 OrderNo
        $orderNo = '2021123456789';
        $this->request->setOrderNo($orderNo);
        $this->assertEquals($orderNo, $this->request->getOrderNo());

        // 测试 DeliveryNo
        $deliveryNo = 'SF1234567890';
        $this->request->setDeliveryNo($deliveryNo);
        $this->assertEquals($deliveryNo, $this->request->getDeliveryNo());

        // 测试 BeginTime
        $beginTime = CarbonImmutable::createFromTimestamp(1625097600);
        $this->request->setBeginTime($beginTime);
        $this->assertSame($beginTime, $this->request->getBeginTime());

        // 测试 EndTime
        $endTime = CarbonImmutable::createFromTimestamp(1627689600);
        $this->request->setEndTime($endTime);
        $this->assertSame($endTime, $this->request->getEndTime());

        // 测试 SortDirect
        $sortDirect = SortDirect::DESC;
        $this->request->setSortDirect($sortDirect);
        $this->assertSame($sortDirect, $this->request->getSortDirect());
    }
}
