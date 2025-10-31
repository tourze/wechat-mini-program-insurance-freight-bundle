<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnOrderNotFoundException;

/**
 * @internal
 */
#[CoversClass(ReturnOrderNotFoundException::class)]
final class ReturnOrderNotFoundExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testExceptionMessage(): void
    {
        $shopOrderId = 'test_shop_order_123';
        $exception = new ReturnOrderNotFoundException($shopOrderId);

        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertEquals('找不到退货单: test_shop_order_123', $exception->getMessage());
    }
}
