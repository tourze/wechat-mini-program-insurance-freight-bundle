<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
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
        // 测试 Account (继承自 WithAccountRequest)
        // Mock Account 具体类的原因：
        // 1. Account 是 Doctrine Entity，虽然实现了多个接口但没有专用的业务接口
        // 2. 测试重点在验证 setter/getter 方法，不涉及具体的数据库操作
        // 3. Mock 可以避免复杂的 Entity 初始化和数据库依赖
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
    }
}
