<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatMiniProgramInsuranceFreightBundle\DependencyInjection\WechatMiniProgramInsuranceFreightExtension;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

class WechatMiniProgramInsuranceFreightExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        $extension = new WechatMiniProgramInsuranceFreightExtension();
        
        $extension->load([], $container);
        
        $this->assertTrue($container->hasDefinition(InsuranceFreightService::class));
    }
}