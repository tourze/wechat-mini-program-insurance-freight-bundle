<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramInsuranceFreightBundle\Command\SyncSingleReturnOrderCommand;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnOrderNotFoundException;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

class SyncSingleReturnOrderCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $returnOrderRepository = $this->createMock(ReturnOrderRepository::class);
        $insuranceFreightService = $this->createMock(InsuranceFreightService::class);
        
        $returnOrder = new ReturnOrder();
        $returnOrderRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['shopOrderId' => 'test_shop_order_id'])
            ->willReturn($returnOrder);
        
        $command = new SyncSingleReturnOrderCommand($returnOrderRepository, $insuranceFreightService);
        
        $application = new Application();
        $application->add($command);
        
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'shopOrderId' => 'test_shop_order_id',
        ]);
        
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
    
    public function testExecuteThrowsExceptionWhenOrderNotFound(): void
    {
        $returnOrderRepository = $this->createMock(ReturnOrderRepository::class);
        $insuranceFreightService = $this->createMock(InsuranceFreightService::class);
        
        $returnOrderRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['shopOrderId' => 'test_shop_order_id'])
            ->willReturn(null);
        
        $command = new SyncSingleReturnOrderCommand($returnOrderRepository, $insuranceFreightService);
        
        $this->expectException(ReturnOrderNotFoundException::class);
        
        $application = new Application();
        $application->add($command);
        
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'shopOrderId' => 'test_shop_order_id',
        ]);
    }
}