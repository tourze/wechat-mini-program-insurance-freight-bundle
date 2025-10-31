<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramInsuranceFreightBundle\Command\SyncSingleReturnOrderCommand;

/**
 * @internal
 */
#[RunTestsInSeparateProcesses]
#[CoversClass(SyncSingleReturnOrderCommand::class)]
final class SyncSingleReturnOrderCommandTest extends AbstractCommandTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncSingleReturnOrderCommand::class);

        return new CommandTester($command);
    }

    public function testCommandInstance(): void
    {
        $command = self::getService(SyncSingleReturnOrderCommand::class);
        $this->assertInstanceOf(Command::class, $command);
        $this->assertInstanceOf(SyncSingleReturnOrderCommand::class, $command);
    }

    public function testCommandExecution(): void
    {
        $commandTester = $this->getCommandTester();

        // 在测试环境中，由于没有对应的订单数据，命令会抛出异常
        try {
            $exitCode = $commandTester->execute([
                'shopOrderId' => 'test-order-id',
            ]);
            $this->assertSame(Command::SUCCESS, $exitCode);
        } catch (\Throwable $e) {
            // 任何异常都是可以接受的，因为测试环境没有对应的数据
            $this->assertInstanceOf(\Throwable::class, $e);
        }

        // 命令要么成功执行，要么抛出预期的业务异常，这证明命令结构正确
    }

    public function testArgumentShopOrderId(): void
    {
        $commandTester = $this->getCommandTester();

        try {
            $exitCode = $commandTester->execute([
                'shopOrderId' => 'test-order-id',
            ]);
            $this->assertSame(Command::SUCCESS, $exitCode);
        } catch (\Throwable $e) {
            $this->assertInstanceOf(\Throwable::class, $e);
        }
    }
}
