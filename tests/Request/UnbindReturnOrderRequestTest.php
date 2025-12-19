<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\UnbindReturnOrderRequest;

/**
 * @internal
 */
#[CoversClass(UnbindReturnOrderRequest::class)]
final class UnbindReturnOrderRequestTest extends RequestTestCase
{
    private UnbindReturnOrderRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UnbindReturnOrderRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('/cgi-bin/express/delivery/no_worry_return/unbind', $this->request->getRequestPath());
    }

    public function testGetRequestOptionsWithValidData(): void
    {
        $account = new Account();

        $this->request->setAccount($account);
        $this->request->setReturnId('RETURN123456789');

        // 获取请求选项
        $options = $this->request->getRequestOptions();

        // 验证结果
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertArrayHasKey('return_id', $json);
        $this->assertEquals('RETURN123456789', $json['return_id']);
    }

    public function testGettersAndSetters(): void
    {
        $account = new Account();
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());

        $returnId = 'RETURN123456789';
        $this->request->setReturnId($returnId);
        $this->assertEquals($returnId, $this->request->getReturnId());
    }
}
