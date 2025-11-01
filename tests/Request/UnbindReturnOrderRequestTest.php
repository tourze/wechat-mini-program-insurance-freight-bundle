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
        // 准备测试数据
        // Mock具体类说明: WechatMiniProgramBundle\Entity\Account是数据实体类，
        // 没有对应的接口定义，测试中需要模拟其行为来验证请求选项的构建逻辑。
        // 使用具体类Mock是合理的，因为Entity类主要包含数据属性和简单的getter/setter方法。
        // 替代方案：可以考虑创建测试专用的Entity工厂类，但当前Mock方式更直观简洁。
        $account = $this->createMock(Account::class);

        // 设置请求参数
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
        // 测试 Account
        // Mock具体类说明: WechatMiniProgramBundle\Entity\Account是数据实体类，
        // 没有对应的接口定义，测试中需要模拟其行为来验证业务逻辑。
        // 使用具体类Mock是合理的，因为Entity类主要包含数据属性和简单的getter/setter方法。
        // 替代方案：可以考虑创建测试专用的Entity工厂类，但当前Mock方式更直观简洁。
        $account = $this->createMock(Account::class);
        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());

        // 测试 ReturnId
        $returnId = 'RETURN123456789';
        $this->request->setReturnId($returnId);
        $this->assertEquals($returnId, $this->request->getReturnId());
    }
}
