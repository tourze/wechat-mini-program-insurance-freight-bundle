<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnOrderNotFoundException;

class ReturnOrderNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $shopOrderId = 'test_shop_order_123';
        $exception = new ReturnOrderNotFoundException($shopOrderId);
        
        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertEquals('找不到退货单: test_shop_order_123', $exception->getMessage());
    }
}