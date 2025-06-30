<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Command\SyncInsuranceOrderListCommand;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

class SyncInsuranceOrderListCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $accountRepository = $this->createMock(AccountRepository::class);
        $insuranceOrderRepository = $this->createMock(InsuranceOrderRepository::class);
        $client = $this->createMock(Client::class);
        $insuranceFreightService = $this->createMock(InsuranceFreightService::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([]);
        
        $command = new SyncInsuranceOrderListCommand(
            $accountRepository,
            $insuranceOrderRepository,
            $client,
            $insuranceFreightService,
            $entityManager
        );
        
        $application = new Application();
        $application->add($command);
        
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}