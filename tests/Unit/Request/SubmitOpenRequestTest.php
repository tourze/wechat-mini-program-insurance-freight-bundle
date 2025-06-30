<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\SubmitOpenRequest;

class SubmitOpenRequestTest extends TestCase
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
        $this->assertIsArray($options);
        $this->assertEmpty($options);
    }
    
    public function testAccount(): void
    {
        // 测试 Account (继承自 WithAccountRequest)
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
    }
}