<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use WechatMiniProgramInsuranceFreightBundle\EventSubscriber\WechatServerCallbackSubscriber;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;

class WechatServerCallbackSubscriberTest extends TestCase
{
    public function testInstantiation(): void
    {
        $orderRepository = $this->createMock(InsuranceOrderRepository::class);
        $logger = $this->createMock(LoggerInterface::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $subscriber = new WechatServerCallbackSubscriber($orderRepository, $logger, $entityManager);
        
        $this->assertInstanceOf(WechatServerCallbackSubscriber::class, $subscriber);
    }
}