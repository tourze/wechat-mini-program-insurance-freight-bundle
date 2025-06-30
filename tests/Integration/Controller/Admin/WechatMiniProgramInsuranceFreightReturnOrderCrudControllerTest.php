<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Controller\Admin;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Controller\Admin\WechatMiniProgramInsuranceFreightReturnOrderCrudController;

class WechatMiniProgramInsuranceFreightReturnOrderCrudControllerTest extends TestCase
{
    public function testControllerCreation(): void
    {
        $controller = new WechatMiniProgramInsuranceFreightReturnOrderCrudController();
        
        $this->assertInstanceOf(WechatMiniProgramInsuranceFreightReturnOrderCrudController::class, $controller);
        $this->assertEquals('WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder', $controller::getEntityFqcn());
    }
}