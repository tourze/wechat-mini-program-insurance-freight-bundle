<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnIdEmptyException;

/**
 * @internal
 */
#[CoversClass(ReturnIdEmptyException::class)]
final class ReturnIdEmptyExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testExceptionMessage(): void
    {
        $exception = new ReturnIdEmptyException();
        $this->assertSame('退货ID不能为空', $exception->getMessage());
    }

    public function testExceptionCode(): void
    {
        $exception = new ReturnIdEmptyException();
        $this->assertSame(0, $exception->getCode());
    }

    public function testExceptionIsThrowable(): void
    {
        $exception = new ReturnIdEmptyException();
        $this->assertInstanceOf(\Throwable::class, $exception);
    }

    public function testExceptionCanBeThrownAndCaught(): void
    {
        $this->expectException(ReturnIdEmptyException::class);
        $this->expectExceptionMessage('退货ID不能为空');

        throw new ReturnIdEmptyException();
    }
}
