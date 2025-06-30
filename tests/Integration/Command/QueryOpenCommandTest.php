<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Command\QueryOpenCommand;

class QueryOpenCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $accountRepository = $this->createMock(AccountRepository::class);
        $client = $this->createMock(Client::class);
        
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([]);
        
        $command = new QueryOpenCommand($accountRepository, $client);
        
        $application = new Application();
        $application->add($command);
        
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}