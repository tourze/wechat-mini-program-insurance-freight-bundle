<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\MockObject\MockObject;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\EventSubscriber\ReturnOrderListener;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnOrderValidationException;

/**
 * @internal
 */
#[CoversClass(ReturnOrderListener::class)]
#[RunTestsInSeparateProcesses]
final class ReturnOrderListenerTest extends AbstractIntegrationTestCase
{
    /** @var Client&MockObject */
    private Client $clientMock;

    /** @var UserLoaderInterface&MockObject */
    private UserLoaderInterface $userLoaderMock;

    protected function onSetUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->userLoaderMock = $this->createMock(UserLoaderInterface::class);

        // 将 mock 服务注册到容器（跳过已初始化的 LoggerInterface）
        self::getContainer()->set(Client::class, $this->clientMock);
        self::getContainer()->set(UserLoaderInterface::class, $this->userLoaderMock);
    }

    public function testPrePersist(): void
    {
        // 设置非测试环境以执行完整逻辑
        $_ENV['APP_ENV'] = 'prod';

        // 从容器获取服务（会自动注入 mock 依赖）
        $listener = self::getService(ReturnOrderListener::class);

        /** @var ReturnOrder&MockObject $order */
        $order = $this->createMock(ReturnOrder::class);

        $order->method('getOpenId')->willReturn('test_open_id');
        $this->userLoaderMock->method('loadUserByOpenId')->willReturn(null);

        $this->expectException(ReturnOrderValidationException::class);
        $this->expectExceptionMessage('找不到小程序用户');

        $listener->prePersist($order);
    }

    public function testPostRemove(): void
    {
        // 从容器获取服务（会自动注入 mock 依赖）
        $listener = self::getService(ReturnOrderListener::class);

        /** @var ReturnOrder&MockObject $order */
        $order = $this->createMock(ReturnOrder::class);

        $order->method('getReturnId')->willReturn('');
        $this->clientMock->expects($this->never())->method('asyncRequest');

        $listener->postRemove($order);
    }
}
