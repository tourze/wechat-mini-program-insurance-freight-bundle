<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramInsuranceFreightBundle\Controller\Admin\WechatMiniProgramInsuranceFreightOrderCrudController;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramInsuranceFreightOrderCrudController::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramInsuranceFreightOrderCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testControllerAccessWithoutAuthentication(): void
    {
        $client = self::createClientWithDatabase();

        $url = $this->generateAdminUrl('index', ['crudControllerFqcn' => WechatMiniProgramInsuranceFreightOrderCrudController::class]);

        $this->expectException(AccessDeniedException::class);
        $client->request('GET', $url);
    }

    public function testControllerInstance(): void
    {
        $controller = new WechatMiniProgramInsuranceFreightOrderCrudController();
        $this->assertInstanceOf(WechatMiniProgramInsuranceFreightOrderCrudController::class, $controller);
    }

    protected function getControllerService(): WechatMiniProgramInsuranceFreightOrderCrudController
    {
        return self::getService(WechatMiniProgramInsuranceFreightOrderCrudController::class);
    }

    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'buyer_open_id' => ['买家OpenID'];
        yield 'order_no' => ['微信支付单号'];
        yield 'status' => ['订单状态'];
        yield 'pay_time' => ['支付时间'];
        yield 'pay_amount' => ['支付金额（分）'];
        yield 'estimate_amount' => ['预估金额（分）'];
        yield 'premium' => ['保费（分）'];
        yield 'delivery_no' => ['快递单号'];
        yield 'policy_no' => ['保单号'];
        yield 'insurance_end_date' => ['保险结束时间'];
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
        yield 'openId' => ['openId'];
        yield 'orderNo' => ['orderNo'];
        yield 'status' => ['status'];
        yield 'payTime' => ['payTime'];
        yield 'payAmount' => ['payAmount'];
        yield 'estimateAmount' => ['estimateAmount'];
        yield 'premium' => ['premium'];
        yield 'deliveryNo' => ['deliveryNo'];
        yield 'policyNo' => ['policyNo'];
        yield 'insuranceEndDate' => ['insuranceEndDate'];
        yield 'refundDeliveryNo' => ['refundDeliveryNo'];
        yield 'refundCompany' => ['refundCompany'];
        yield 'homePickUp' => ['homePickUp'];
        yield 'orderPath' => ['orderPath'];
        yield 'goodsList' => ['goodsList'];
        yield 'reportNo' => ['reportNo'];
    }

    /**
     * 提供编辑页字段数据
     *
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'openId' => ['openId'];
        yield 'orderNo' => ['orderNo'];
        yield 'status' => ['status'];
        yield 'payTime' => ['payTime'];
        yield 'payAmount' => ['payAmount'];
        yield 'estimateAmount' => ['estimateAmount'];
        yield 'premium' => ['premium'];
        yield 'deliveryNo' => ['deliveryNo'];
        yield 'policyNo' => ['policyNo'];
        yield 'insuranceEndDate' => ['insuranceEndDate'];
        yield 'refundDeliveryNo' => ['refundDeliveryNo'];
        yield 'refundCompany' => ['refundCompany'];
        yield 'homePickUp' => ['homePickUp'];
        yield 'orderPath' => ['orderPath'];
        yield 'goodsList' => ['goodsList'];
        yield 'reportNo' => ['reportNo'];
    }
}
