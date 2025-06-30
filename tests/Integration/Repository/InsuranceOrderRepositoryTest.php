<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;

class InsuranceOrderRepositoryTest extends TestCase
{
    private InsuranceOrderRepository $repository;
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $registry;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);
        
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->name = InsuranceOrder::class;
        
        $this->entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with(InsuranceOrder::class)
            ->willReturn($classMetadata);
        
        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->with(InsuranceOrder::class)
            ->willReturn($this->entityManager);
        
        $this->repository = new InsuranceOrderRepository($this->registry);
    }
    
    public function testConstruction(): void
    {
        $this->assertInstanceOf(InsuranceOrderRepository::class, $this->repository);
    }
    
    public function testGetClassName(): void
    {
        $this->assertEquals(InsuranceOrder::class, $this->repository->getClassName());
    }
}