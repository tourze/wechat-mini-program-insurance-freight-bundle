<?php

namespace WechatMiniProgramInsuranceFreightBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;

/**
 * @method ReturnOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReturnOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReturnOrder[]    findAll()
 * @method ReturnOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReturnOrderRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnOrder::class);
    }
}
