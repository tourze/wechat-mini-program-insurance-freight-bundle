<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;
use WechatMiniProgramInsuranceFreightBundle\Repository\SummaryRepository;

/**
 * @template-extends AbstractRepositoryTestCase<Summary>
 * @internal
 */
#[CoversClass(SummaryRepository::class)]
#[RunTestsInSeparateProcesses]
final class SummaryRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        $entity = new Summary();
        $entity->setDate(new \DateTimeImmutable());
        $entity->setTotal(mt_rand(50, 200));
        $entity->setClaimNum(mt_rand(5, 20));
        $entity->setClaimSuccessNum(mt_rand(3, 15));
        $entity->setPremium(mt_rand(300, 1000));
        $entity->setFunds(mt_rand(2000, 8000));
        $entity->setNeedClose(false);

        return $entity;
    }

    /**
     * @return SummaryRepository
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(SummaryRepository::class);
    }

    protected function onSetUp(): void
    {
        // 检查当前测试是否需要 DataFixtures 数据
        $currentTest = $this->name();
        if ('testCountWithDataFixtureShouldReturnGreaterThanZero' === $currentTest) {
            // 为计数测试创建测试数据
            $this->createTestDataForCountTest();
        }
    }

    private function createTestDataForCountTest(): void
    {
        $entity = new Summary();
        $entity->setDate(new \DateTimeImmutable());
        $entity->setTotal(100);
        $entity->setClaimNum(10);
        $entity->setClaimSuccessNum(8);
        $entity->setPremium(500);
        $entity->setFunds(4000);
        $entity->setNeedClose(false);

        $this->getRepository()->save($entity, true);
    }

    public function testFindByAccountNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['account' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByAccountNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['account' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByTotalNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['total' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByTotalNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['total' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByClaimNumNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['claimNum' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByClaimNumNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['claimNum' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByClaimSuccessNumNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['claimSuccessNum' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByClaimSuccessNumNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['claimSuccessNum' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByPremiumNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['premium' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByPremiumNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['premium' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByFundsNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['funds' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByFundsNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['funds' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByNeedCloseNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['needClose' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByNeedCloseNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['needClose' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testSave(): void
    {
        $summary = new Summary();
        $summary->setDate(new \DateTimeImmutable());
        $summary->setTotal(100);
        $summary->setClaimNum(10);
        $summary->setClaimSuccessNum(8);
        $summary->setPremium(500);
        $summary->setFunds(4000);
        $summary->setNeedClose(false);

        $this->getRepository()->save($summary, true);

        $this->assertNotNull($summary->getId());
    }

    public function testRemove(): void
    {
        $summary = new Summary();
        $summary->setDate(new \DateTimeImmutable());
        $summary->setTotal(100);
        $summary->setClaimNum(10);
        $summary->setClaimSuccessNum(8);
        $summary->setPremium(500);
        $summary->setFunds(4000);
        $summary->setNeedClose(false);

        $this->getRepository()->save($summary, true);
        $id = $summary->getId();

        $this->getRepository()->remove($summary, true);

        $this->assertNull($this->getRepository()->find($id));
    }
}
