<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\SubmitOpenRequest;

/**
 * @internal
 */
#[CoversClass(SubmitOpenRequest::class)]
final class SubmitOpenRequestTest extends RequestTestCase
{
    private SubmitOpenRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new SubmitOpenRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('/wxa/business/insurance_freight/open', $this->request->getRequestPath());
    }

    public function testGetRequestOptions(): void
    {
        // 获取请求选项
        $options = $this->request->getRequestOptions();

        // 验证结果
        $this->assertEmpty($options);
    }

    public function testAccount(): void
    {
        $account = new Account();
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
    }
}
