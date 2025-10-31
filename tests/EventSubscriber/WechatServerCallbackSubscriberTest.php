<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use WechatMiniProgramInsuranceFreightBundle\EventSubscriber\WechatServerCallbackSubscriber;
use WechatMiniProgramServerMessageBundle\Event\ServerMessageRequestEvent;

/**
 * @internal
 */
#[CoversClass(WechatServerCallbackSubscriber::class)]
#[RunTestsInSeparateProcesses]
final class WechatServerCallbackSubscriberTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // 该测试类不需要额外的设置
    }

    public function testGetSubscribedEvents(): void
    {
        $events = WechatServerCallbackSubscriber::getSubscribedEvents();
        $this->assertArrayHasKey('WechatMiniProgramServerMessageBundle\Event\ServerMessageRequestEvent', $events);
        $this->assertEquals(['onServerMessageRequest', 0], $events['WechatMiniProgramServerMessageBundle\Event\ServerMessageRequestEvent']);
    }

    /**
     * 测试 WechatServerCallbackSubscriber 的构造函数功能
     */
    public function testConstructor(): void
    {
        // 在集成测试中，我们从容器获取服务实例
        $subscriber = self::getService(WechatServerCallbackSubscriber::class);

        // 验证服务已正确初始化
        $this->assertInstanceOf(WechatServerCallbackSubscriber::class, $subscriber);
    }

    /**
     * 测试默认配置
     */
    public function testDefaultConfiguration(): void
    {
        // 在集成测试中，我们验证服务的默认配置通过容器正确设置
        $subscriber = self::getService(WechatServerCallbackSubscriber::class);

        // 验证依赖服务已正确注入
        $this->assertInstanceOf(WechatServerCallbackSubscriber::class, $subscriber);
    }

    /**
     * 测试 onServerMessageRequest 方法 - 非理赔结果消息
     */
    public function testOnServerMessageRequestWithNonClaimResultMessage(): void
    {
        $subscriber = self::getService(WechatServerCallbackSubscriber::class);

        $event = $this->createMock(ServerMessageRequestEvent::class);
        $event->expects($this->once())
            ->method('getMessage')
            ->willReturn(['Event' => 'other_event'])
        ;

        // 应该直接返回，不进行任何处理
        $subscriber->onServerMessageRequest($event);

        // 没有异常抛出表示测试通过
    }

    /**
     * 测试 onServerMessageRequest 方法 - 数据格式错误
     */
    public function testOnServerMessageRequestWithInvalidData(): void
    {
        $subscriber = self::getService(WechatServerCallbackSubscriber::class);

        $event = $this->createMock(ServerMessageRequestEvent::class);
        $event->expects($this->once())
            ->method('getMessage')
            ->willReturn(['Event' => 'wxainsurance_claim_result'])
        ;

        // 应该因为缺少 upload_event 而直接返回
        $subscriber->onServerMessageRequest($event);

        // 没有异常抛出表示测试通过
    }
}
