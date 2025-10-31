<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnOrderValidationException;

/**
 * @internal
 */
#[CoversClass(ReturnOrderValidationException::class)]
final class ReturnOrderValidationExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testExceptionMessage(): void
    {
        $message = '测试异常消息';
        $exception = new ReturnOrderValidationException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionExtendsRuntimeException(): void
    {
        $exception = new ReturnOrderValidationException('测试');
        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }
}
