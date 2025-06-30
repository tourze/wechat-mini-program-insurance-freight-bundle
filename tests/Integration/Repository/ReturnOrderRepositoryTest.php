<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;

class ReturnOrderRepositoryTest extends TestCase
{
    private ReturnOrderRepository $repository;
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $registry;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);
        
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->name = ReturnOrder::class;
        
        $this->entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with(ReturnOrder::class)
            ->willReturn($classMetadata);
        
        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->with(ReturnOrder::class)
            ->willReturn($this->entityManager);
        
        $this->repository = new ReturnOrderRepository($this->registry);
    }
    
    public function testConstruction(): void
    {
        $this->assertInstanceOf(ReturnOrderRepository::class, $this->repository);
    }
    
    public function testGetClassName(): void
    {
        $this->assertEquals(ReturnOrder::class, $this->repository->getClassName());
    }
}