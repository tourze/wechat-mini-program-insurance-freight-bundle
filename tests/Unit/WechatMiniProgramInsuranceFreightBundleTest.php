<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\WechatMiniProgramInsuranceFreightBundle;

class WechatMiniProgramInsuranceFreightBundleTest extends TestCase
{
    public function testBundleCreation(): void
    {
        $bundle = new WechatMiniProgramInsuranceFreightBundle();
        
        $this->assertInstanceOf(WechatMiniProgramInsuranceFreightBundle::class, $bundle);
    }
}