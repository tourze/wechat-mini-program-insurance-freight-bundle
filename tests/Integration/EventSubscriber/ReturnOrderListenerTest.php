<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\EventSubscriber\ReturnOrderListener;

class ReturnOrderListenerTest extends TestCase
{
    public function testInstantiation(): void
    {
        $client = $this->createMock(Client::class);
        $userLoader = $this->createMock(UserLoaderInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        
        $listener = new ReturnOrderListener($client, $userLoader, $logger);
        
        $this->assertInstanceOf(ReturnOrderListener::class, $listener);
    }
}