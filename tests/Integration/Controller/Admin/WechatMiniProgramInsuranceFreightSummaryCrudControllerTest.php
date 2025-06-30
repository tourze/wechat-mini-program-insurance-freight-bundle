<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Controller\Admin;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Controller\Admin\WechatMiniProgramInsuranceFreightSummaryCrudController;

class WechatMiniProgramInsuranceFreightSummaryCrudControllerTest extends TestCase
{
    public function testControllerCreation(): void
    {
        $controller = new WechatMiniProgramInsuranceFreightSummaryCrudController();
        
        $this->assertInstanceOf(WechatMiniProgramInsuranceFreightSummaryCrudController::class, $controller);
        $this->assertEquals('WechatMiniProgramInsuranceFreightBundle\Entity\Summary', $controller::getEntityFqcn());
    }
}