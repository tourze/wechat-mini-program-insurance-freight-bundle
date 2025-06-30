<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

class InsuranceFreightServiceTest extends TestCase
{
    public function testServiceCreation(): void
    {
        $client = $this->createMock(Client::class);
        $logger = $this->createMock(LoggerInterface::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $service = new InsuranceFreightService($client, $logger, $entityManager);
        
        $this->assertInstanceOf(InsuranceFreightService::class, $service);
    }
}