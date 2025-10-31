<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatMiniProgramInsuranceFreightBundle\Exception\InsuranceOrderValidationException;

/**
 * @internal
 */
#[CoversClass(InsuranceOrderValidationException::class)]
final class InsuranceOrderValidationExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testExceptionMessage(): void
    {
        $message = '测试异常消息';
        $exception = new InsuranceOrderValidationException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionExtendsRuntimeException(): void
    {
        $exception = new InsuranceOrderValidationException('测试');
        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }
}
