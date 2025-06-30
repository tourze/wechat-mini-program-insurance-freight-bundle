<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;
use WechatMiniProgramInsuranceFreightBundle\Repository\SummaryRepository;

class SummaryRepositoryTest extends TestCase
{
    private SummaryRepository $repository;
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $registry;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);
        
        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->name = Summary::class;
        
        $this->entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with(Summary::class)
            ->willReturn($classMetadata);
        
        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->with(Summary::class)
            ->willReturn($this->entityManager);
        
        $this->repository = new SummaryRepository($this->registry);
    }
    
    public function testConstruction(): void
    {
        $this->assertInstanceOf(SummaryRepository::class, $this->repository);
    }
    
    public function testGetClassName(): void
    {
        $this->assertEquals(Summary::class, $this->repository->getClassName());
    }
}