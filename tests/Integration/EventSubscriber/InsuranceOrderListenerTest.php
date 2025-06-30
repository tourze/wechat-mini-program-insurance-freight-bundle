<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\EventSubscriber\InsuranceOrderListener;

class InsuranceOrderListenerTest extends TestCase
{
    public function testInstantiation(): void
    {
        $client = $this->createMock(Client::class);
        $userLoader = $this->createMock(UserLoaderInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        
        $listener = new InsuranceOrderListener($client, $userLoader, $logger);
        
        $this->assertInstanceOf(InsuranceOrderListener::class, $listener);
    }
}