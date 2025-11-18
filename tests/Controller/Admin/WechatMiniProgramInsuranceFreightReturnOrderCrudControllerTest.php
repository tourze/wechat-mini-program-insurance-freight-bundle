<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramInsuranceFreightBundle\Controller\Admin\WechatMiniProgramInsuranceFreightReturnOrderCrudController;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramInsuranceFreightReturnOrderCrudController::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramInsuranceFreightReturnOrderCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testDumpIndexHtml(): void
    {
        // 调试用：抓取 Index 页 HTML，便于定位问题
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', $this->generateAdminUrl('index'));
        @file_put_contents('/tmp/return_index.html', $crawler->html());

        // 添加断言：确保请求成功
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('退货单列表', $crawler->html());
    }

    public function testControllerAccessWithoutAuthentication(): void
    {
        $client = self::createClientWithDatabase();

        $url = $this->generateAdminUrl('index', ['crudControllerFqcn' => WechatMiniProgramInsuranceFreightReturnOrderCrudController::class]);

        $this->expectException(AccessDeniedException::class);
        $client->request('GET', $url);
    }

    public function testControllerInstance(): void
    {
        $controller = new WechatMiniProgramInsuranceFreightReturnOrderCrudController();
        $this->assertInstanceOf(WechatMiniProgramInsuranceFreightReturnOrderCrudController::class, $controller);
    }

    protected function getControllerService(): WechatMiniProgramInsuranceFreightReturnOrderCrudController
    {
        return self::getService(WechatMiniProgramInsuranceFreightReturnOrderCrudController::class);
    }

    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'shop_order_id' => ['商家退货编号'];
        yield 'user_open_id' => ['用户OpenID'];
        yield 'order_no' => ['微信支付单号'];
        yield 'waybill_id' => ['运单号'];
        yield 'return_status' => ['退货状态'];
        yield 'waybill_status' => ['运单状态'];
        yield 'express_company_name' => ['运力公司名称'];
        yield 'express_company_code' => ['运力公司编码'];
        yield 'return_id' => ['退货ID'];
        yield 'report_no' => ['理赔报案号'];
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
        yield 'shopOrderId' => ['shopOrderId'];
        yield 'openId' => ['openId'];
        yield 'wxPayId' => ['wxPayId'];
        yield 'waybillId' => ['waybillId'];
        yield 'status' => ['status'];
        yield 'orderStatus' => ['orderStatus'];
        yield 'deliveryName' => ['deliveryName'];
        yield 'deliveryId' => ['deliveryId'];
        yield 'returnId' => ['returnId'];
        yield 'reportNo' => ['reportNo'];
        yield 'orderPath' => ['orderPath'];
        yield 'bizAddress' => ['bizAddressString'];
        yield 'userAddress' => ['userAddressString'];
        yield 'goodsList' => ['goodsListString'];
    }

    /**
     * 提供编辑页字段数据
     *
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'shopOrderId' => ['shopOrderId'];
        yield 'openId' => ['openId'];
        yield 'wxPayId' => ['wxPayId'];
        yield 'waybillId' => ['waybillId'];
        yield 'status' => ['status'];
        yield 'orderStatus' => ['orderStatus'];
        yield 'deliveryName' => ['deliveryName'];
        yield 'deliveryId' => ['deliveryId'];
        yield 'returnId' => ['returnId'];
        yield 'reportNo' => ['reportNo'];
        yield 'orderPath' => ['orderPath'];
        yield 'bizAddress' => ['bizAddressString'];
        yield 'userAddress' => ['userAddressString'];
        yield 'goodsList' => ['goodsListString'];
    }
}
