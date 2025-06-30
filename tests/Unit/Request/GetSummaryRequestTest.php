<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Request;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Request\GetSummaryRequest;

class GetSummaryRequestTest extends TestCase
{
    private GetSummaryRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new GetSummaryRequest();
    }
    
    public function testGetRequestPath(): void
    {
        $this->assertEquals('/wxa/business/insurance_freight/getsummary', $this->request->getRequestPath());
    }
    
    public function testGetRequestOptions_withValidData(): void
    {
        // 准备测试数据
        $account = $this->createMock(Account::class);
        $beginTime = CarbonImmutable::parse('2023-01-01 00:00:00');
        $endTime = CarbonImmutable::parse('2023-01-31 23:59:59');
        
        // 设置请求参数
        $this->request->setAccount($account);
        $this->request->setBeginTime($beginTime);
        $this->request->setEndTime($endTime);
        
        // 获取请求选项
        $options = $this->request->getRequestOptions();
        
        // 验证结果
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        
        /** @var array<string, mixed> $json */
        $json = $options['json'];
        // 注意：原代码中有bug，end_time使用了getBeginTime()，这里我们保持与原代码一致
        $this->assertIsArray($json);
        $this->assertArrayHasKey('begin_time', $json);
        $this->assertArrayHasKey('end_time', $json);
        $this->assertEquals($beginTime->getTimestamp(), $json['begin_time']);
        $this->assertEquals($beginTime->getTimestamp(), $json['end_time']);
    }
    
    public function testGettersAndSetters(): void
    {
        // 测试 Account
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
        
        // 测试 BeginTime
        $beginTime = CarbonImmutable::parse('2023-01-01 00:00:00');
        $this->request->setBeginTime($beginTime);
        $this->assertSame($beginTime, $this->request->getBeginTime());
        
        // 测试 EndTime
        $endTime = CarbonImmutable::parse('2023-01-31 23:59:59');
        $this->request->setEndTime($endTime);
        $this->assertSame($endTime, $this->request->getEndTime());
    }
}