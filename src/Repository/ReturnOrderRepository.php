<?php

namespace WechatMiniProgramInsuranceFreightBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;

/**
 * @extends ServiceEntityRepository<ReturnOrder>
 */
#[AsRepository(entityClass: ReturnOrder::class)]
class ReturnOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnOrder::class);
    }

    public function save(ReturnOrder $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReturnOrder $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByWechatTradeNo(string $wechatTradeNo): ?ReturnOrder
    {
        return $this->findOneBy(['wxPayId' => $wechatTradeNo]);
    }
}
