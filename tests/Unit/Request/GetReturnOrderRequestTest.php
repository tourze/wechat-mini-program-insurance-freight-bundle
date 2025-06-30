<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\GetReturnOrderRequest;

class GetReturnOrderRequestTest extends TestCase
{
    private GetReturnOrderRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new GetReturnOrderRequest();
    }
    
    public function testGetRequestPath(): void
    {
        $this->assertEquals('/cgi-bin/express/delivery/no_worry_return/get', $this->request->getRequestPath());
    }
    
    public function testGetRequestOptions_withValidData(): void
    {
        // 准备测试数据
        $account = $this->createMock(Account::class);
        
        // 设置请求参数
        $this->request->setAccount($account);
        $this->request->setReturnId('RETURN123456789');
        
        // 获取请求选项
        $options = $this->request->getRequestOptions();
        
        // 验证结果
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        
        /** @var array<string, mixed> $json */
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('return_id', $json);
        $this->assertEquals('RETURN123456789', $json['return_id']);
    }
    
    public function testGettersAndSetters(): void
    {
        // 测试 Account
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
        
        // 测试 ReturnId
        $returnId = 'RETURN123456789';
        $this->request->setReturnId($returnId);
        $this->assertEquals($returnId, $this->request->getReturnId());
    }
}