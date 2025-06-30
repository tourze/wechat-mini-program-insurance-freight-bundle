<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Command\GetSummaryCommand;
use WechatMiniProgramInsuranceFreightBundle\Repository\SummaryRepository;

class GetSummaryCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $accountRepository = $this->createMock(AccountRepository::class);
        $client = $this->createMock(Client::class);
        $summaryRepository = $this->createMock(SummaryRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->willReturn([]);
        
        $command = new GetSummaryCommand($accountRepository, $client, $summaryRepository, $entityManager);
        
        $application = new Application();
        $application->add($command);
        
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}