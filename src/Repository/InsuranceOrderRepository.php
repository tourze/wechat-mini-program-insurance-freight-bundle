<?php

namespace WechatMiniProgramInsuranceFreightBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;

/**
 * @method InsuranceOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method InsuranceOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method InsuranceOrder[]    findAll()
 * @method InsuranceOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InsuranceOrderRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InsuranceOrder::class);
    }
}
