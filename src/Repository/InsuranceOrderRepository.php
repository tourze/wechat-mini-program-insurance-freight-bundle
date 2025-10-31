<?php

namespace WechatMiniProgramInsuranceFreightBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;

/**
 * @extends ServiceEntityRepository<InsuranceOrder>
 */
#[AsRepository(entityClass: InsuranceOrder::class)]
class InsuranceOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InsuranceOrder::class);
    }

    public function save(InsuranceOrder $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InsuranceOrder $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByWechatTradeNo(string $wechatTradeNo): ?InsuranceOrder
    {
        return $this->findOneBy(['orderNo' => $wechatTradeNo]);
    }
}
