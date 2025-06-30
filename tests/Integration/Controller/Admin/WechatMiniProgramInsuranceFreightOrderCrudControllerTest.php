<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Controller\Admin;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Controller\Admin\WechatMiniProgramInsuranceFreightOrderCrudController;

class WechatMiniProgramInsuranceFreightOrderCrudControllerTest extends TestCase
{
    public function testControllerCreation(): void
    {
        $controller = new WechatMiniProgramInsuranceFreightOrderCrudController();
        
        $this->assertInstanceOf(WechatMiniProgramInsuranceFreightOrderCrudController::class, $controller);
        $this->assertEquals('WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder', $controller::getEntityFqcn());
    }
}