<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatMiniProgramInsuranceFreightBundle\Exception\AccountNotFoundException;

/**
 * @internal
 */
#[CoversClass(AccountNotFoundException::class)]
final class AccountNotFoundExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testExceptionMessage(): void
    {
        $exception = new AccountNotFoundException();
        $this->assertSame('退货订单缺少关联账户', $exception->getMessage());
    }

    public function testExceptionExtendsRuntimeException(): void
    {
        $exception = new AccountNotFoundException();
        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }
}
