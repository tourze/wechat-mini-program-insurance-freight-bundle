<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\MessageBusInterface;
use WechatMiniProgramInsuranceFreightBundle\Command\SyncValidReturnOrdersCommand;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;

class SyncValidReturnOrdersCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $returnOrderRepository = $this->createMock(ReturnOrderRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $messageBus = $this->createMock(MessageBusInterface::class);
        
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(Query::class);
        
        $returnOrderRepository->expects($this->once())
            ->method('createQueryBuilder')
            ->with('a')
            ->willReturn($queryBuilder);
            
        $queryBuilder->expects($this->once())
            ->method('where')
            ->willReturn($queryBuilder);
            
        $queryBuilder->expects($this->exactly(2))
            ->method('setParameter')
            ->willReturn($queryBuilder);
            
        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);
            
        $query->expects($this->once())
            ->method('toIterable')
            ->willReturn([]);
        
        $command = new SyncValidReturnOrdersCommand($returnOrderRepository, $entityManager, $messageBus);
        
        $application = new Application();
        $application->add($command);
        
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}