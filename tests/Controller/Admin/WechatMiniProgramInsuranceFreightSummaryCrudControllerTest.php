<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramInsuranceFreightBundle\Controller\Admin\WechatMiniProgramInsuranceFreightSummaryCrudController;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramInsuranceFreightSummaryCrudController::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramInsuranceFreightSummaryCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testControllerAccessWithoutAuthentication(): void
    {
        $client = self::createClientWithDatabase();

        $url = $this->generateAdminUrl('index', ['crudControllerFqcn' => WechatMiniProgramInsuranceFreightSummaryCrudController::class]);

        $this->expectException(AccessDeniedException::class);
        $client->request('GET', $url);
    }

    public function testControllerInstance(): void
    {
        $controller = new WechatMiniProgramInsuranceFreightSummaryCrudController();
        $this->assertInstanceOf(WechatMiniProgramInsuranceFreightSummaryCrudController::class, $controller);
    }

    protected function getControllerService(): WechatMiniProgramInsuranceFreightSummaryCrudController
    {
        return self::getService(WechatMiniProgramInsuranceFreightSummaryCrudController::class);
    }

    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'date' => ['日期'];
        yield 'insure_count' => ['投保总数'];
        yield 'claim_count' => ['理赔总数'];
        yield 'claim_success_count' => ['理赔成功数'];
        yield 'current_premium' => ['当前保费（分）'];
        yield 'current_balance' => ['当前账号余额（分）'];
        yield 'disable_insure' => ['是否不能投保'];
        yield 'create_time' => ['创建时间'];
        yield 'update_time' => ['更新时间'];
    }

    /**
     * 提供新增页字段数据
     *
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'date' => ['date'];
        yield 'total' => ['total'];
        yield 'claimNum' => ['claimNum'];
        yield 'claimSuccessNum' => ['claimSuccessNum'];
        yield 'premium' => ['premium'];
        yield 'funds' => ['funds'];
        yield 'needClose' => ['needClose'];
    }

    /**
     * 提供编辑页字段数据
     *
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'date' => ['date'];
        yield 'total' => ['total'];
        yield 'claimNum' => ['claimNum'];
        yield 'claimSuccessNum' => ['claimSuccessNum'];
        yield 'premium' => ['premium'];
        yield 'funds' => ['funds'];
        yield 'needClose' => ['needClose'];
    }
}
