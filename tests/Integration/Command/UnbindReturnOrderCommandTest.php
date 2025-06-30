<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Command\UnbindReturnOrderCommand;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;

class UnbindReturnOrderCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $returnOrderRepository = $this->createMock(ReturnOrderRepository::class);
        $client = $this->createMock(Client::class);
        
        $account = $this->createMock(Account::class);
        $returnOrder = new ReturnOrder();
        $returnOrder->setAccount($account);
        $returnOrder->setReturnId('test_return_id');
        
        $returnOrderRepository->expects($this->once())
            ->method('find')
            ->with('test_shop_order_id')
            ->willReturn($returnOrder);
        
        $client->expects($this->once())
            ->method('request')
            ->willReturn([]);
        
        $command = new UnbindReturnOrderCommand($returnOrderRepository, $client);
        
        $application = new Application();
        $application->add($command);
        
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'shopOrderId' => 'test_shop_order_id',
        ]);
        
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}