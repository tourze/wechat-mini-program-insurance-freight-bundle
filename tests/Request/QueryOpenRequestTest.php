<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\QueryOpenRequest;

/**
 * @internal
 */
#[CoversClass(QueryOpenRequest::class)]
final class QueryOpenRequestTest extends RequestTestCase
{
    private QueryOpenRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new QueryOpenRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('/wxa/business/insurance_freight/query_open', $this->request->getRequestPath());
    }

    public function testGetRequestOptions(): void
    {
        // 获取请求选项
        $options = $this->request->getRequestOptions();

        // 验证结果
        $this->assertIsArray($options);
        $this->assertArrayHasKey('body', $options);
        $this->assertIsString($options['body']);
        $this->assertEquals('', $options['body']);
    }

    public function testAccount(): void
    {
        $account = new Account();
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
    }
}
