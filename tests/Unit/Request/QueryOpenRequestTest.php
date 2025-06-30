<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\QueryOpenRequest;

class QueryOpenRequestTest extends TestCase
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
        $this->assertEquals('', $options['body']);
    }
    
    public function testAccount(): void
    {
        // 测试 Account (继承自 WithAccountRequest)
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
    }
}