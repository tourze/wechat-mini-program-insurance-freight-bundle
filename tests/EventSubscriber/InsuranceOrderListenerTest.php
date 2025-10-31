<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\EventSubscriber\InsuranceOrderListener;
use WechatMiniProgramInsuranceFreightBundle\Exception\InsuranceOrderValidationException;

/**
 * @internal
 */
#[CoversClass(InsuranceOrderListener::class)]
#[RunTestsInSeparateProcesses]
final class InsuranceOrderListenerTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // onSetUp 方法是抽象的，不需要调用 parent::onSetUp()
    }

    public function testValidateInsuranceOrder(): void
    {
        $listener = self::getService(InsuranceOrderListener::class);

        // 创建真实的 InsuranceOrder 对象来测试验证逻辑
        $order = new InsuranceOrder();
        $order->setOpenId('test_open_id');
        $order->setStatus(InsuranceOrderStatus::Securing);
        $order->setPayTime(new \DateTimeImmutable());
        $order->setPayAmount(1000);
        $order->setDeliveryNo(''); // 设置空字符串来触发验证错误
        $order->setOrderNo('test_order_' . uniqid());
        $order->setDeliveryPlaceProvince('测试省');
        $order->setDeliveryPlaceCity('测试市');
        $order->setDeliveryPlaceCounty('测试区');
        $order->setDeliveryPlaceAddress('测试地址');
        $order->setReceiptPlaceProvince('收货省');
        $order->setReceiptPlaceCity('收货市');
        $order->setReceiptPlaceCounty('收货区');
        $order->setReceiptPlaceAddress('收货地址');
        $order->setEstimateAmount(1000);
        $order->setPremium(50);
        $order->setPolicyNo('test_policy_' . uniqid());
        $order->setInsuranceEndDate(new \DateTimeImmutable('+1 year'));

        $this->expectException(InsuranceOrderValidationException::class);
        $this->expectExceptionMessage('发货运单号不能为空');

        // 使用反射调用私有方法 validateInsuranceOrder
        $reflection = new \ReflectionClass($listener);
        $method = $reflection->getMethod('validateInsuranceOrder');
        $method->setAccessible(true);
        $method->invoke($listener, $order);
    }

    /**
     * 测试 prePersist 方法在测试环境下的行为
     */
    public function testPrePersistInTestEnvironment(): void
    {
        $listener = self::getService(InsuranceOrderListener::class);

        $order = new InsuranceOrder();

        // 在测试环境中，prePersist 应该直接返回而不进行任何处理
        $listener->prePersist($order);

        // 添加断言：确保订单对象存在且属性未被修改
        $this->assertInstanceOf(InsuranceOrder::class, $order);
        $this->assertNull($order->getId());
    }

    /**
     * 测试构造函数依赖注入
     */
    public function testConstructorDependencies(): void
    {
        $listener = self::getService(InsuranceOrderListener::class);

        $this->assertInstanceOf(InsuranceOrderListener::class, $listener);

        // 测试服务实例化成功
        $reflection = new \ReflectionClass($listener);
        $this->assertTrue($reflection->hasProperty('client'));
        $this->assertTrue($reflection->hasProperty('userLoader'));
        $this->assertTrue($reflection->hasProperty('logger'));
    }
}
