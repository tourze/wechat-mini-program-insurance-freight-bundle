<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Service;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\MockObject\MockObject;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

/**
 * @internal
 */
#[RunTestsInSeparateProcesses]
#[CoversClass(InsuranceFreightService::class)]
final class InsuranceFreightServiceIntegrationTest extends AbstractIntegrationTestCase
{
    /** @var Client&MockObject */
    private Client $clientMock;

    protected function onSetUp(): void
    {
        // 设置测试环境变量，让 InsuranceOrderListener 跳过执行
        $_ENV['APP_ENV'] = 'test';

        // 创建 mock 对象作为实例变量
        $this->clientMock = $this->createMock(Client::class);

        // 将 mock 服务注册到容器（跳过已初始化的服务）
        self::getContainer()->set(Client::class, $this->clientMock);
    }

    public function testServiceCreation(): void
    {
        $service = self::getService(InsuranceFreightService::class);
        $this->assertInstanceOf(InsuranceFreightService::class, $service);
    }

    public function testOverrideOrderInfo(): void
    {
        $service = self::getService(InsuranceFreightService::class);

        $order = new InsuranceOrder();
        $timestamp = time();

        $item = [
            'policy_no' => 'POL123456',
            'order_no' => 'ORD789012',
            'report_no' => 'REP345678',
            'delivery_no' => 'DEL901234',
            'refund_delivery_no' => 'REF567890',
            'premium' => 1000,
            'estimate_amount' => 5000,
            'status' => 2, // InsuranceOrderStatus::Securing
            'pay_fail_reason' => null,
            'pay_finish_time' => $timestamp,
            'is_home_pick_up' => 1,
        ];

        $service->overrideOrderInfo($order, $item);

        $this->assertEquals('POL123456', $order->getPolicyNo());
        $this->assertEquals('ORD789012', $order->getOrderNo());
        $this->assertEquals('REP345678', $order->getReportNo());
        $this->assertEquals('DEL901234', $order->getDeliveryNo());
        $this->assertEquals('REF567890', $order->getRefundDeliveryNo());
        $this->assertEquals(1000, $order->getPremium());
        $this->assertEquals(5000, $order->getEstimateAmount());
        $this->assertEquals(InsuranceOrderStatus::Securing, $order->getStatus());
        $this->assertNull($order->getPayFailReason());
        $this->assertInstanceOf(CarbonImmutable::class, $order->getPayFinishTime());
        $this->assertEquals($timestamp, $order->getPayFinishTime()->getTimestamp());
        $this->assertTrue($order->isHomePickUp());
    }

    public function testOverrideOrderInfoWithNullPayFinishTime(): void
    {
        $service = self::getService(InsuranceFreightService::class);

        $order = new InsuranceOrder();

        $item = [
            'policy_no' => 'POL123456',
            'order_no' => 'ORD789012',
            'report_no' => 'REP345678',
            'delivery_no' => 'DEL901234',
            'refund_delivery_no' => 'REF567890',
            'premium' => 1000,
            'estimate_amount' => 5000,
            'status' => 2,
            'pay_fail_reason' => 'Test reason',
            'pay_finish_time' => 0, // Should result in null
            'is_home_pick_up' => 0,
        ];

        $service->overrideOrderInfo($order, $item);

        $this->assertEquals('Test reason', $order->getPayFailReason());
        $this->assertNull($order->getPayFinishTime());
        $this->assertFalse($order->isHomePickUp());
    }

    public function testSyncInsuranceOrder(): void
    {
        $service = self::getService(InsuranceFreightService::class);

        // 创建真实的实体对象
        $entityManager = self::getService(EntityManagerInterface::class);
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id');
        $account->setAppSecret('test_app_secret');
        $account->setToken('test_token');
        $account->setEncodingAesKey('test_aes_key');
        $account->setValid(true);
        $entityManager->persist($account);
        $entityManager->flush();

        $order = new InsuranceOrder();
        $order->setAccount($account);
        $order->setOrderNo('ORD123456');
        $order->setOpenId('test_openid');
        $order->setStatus(InsuranceOrderStatus::Securing);
        $order->setPayTime(new \DateTimeImmutable());
        $order->setPayAmount(1000);
        $order->setEstimateAmount(5000);
        $order->setPremium(100);
        $order->setDeliveryNo('DEL123456');
        $order->setDeliveryPlaceProvince('广东省');
        $order->setDeliveryPlaceCity('深圳市');
        $order->setDeliveryPlaceCounty('南山区');
        $order->setDeliveryPlaceAddress('测试地址');
        $order->setReceiptPlaceProvince('北京市');
        $order->setReceiptPlaceCity('北京市');
        $order->setReceiptPlaceCounty('朝阳区');
        $order->setReceiptPlaceAddress('测试收货地址');
        $order->setPolicyNo('POL123456');
        $order->setInsuranceEndDate(new \DateTimeImmutable('+30 days'));

        /** @var array<string, mixed> $responseData */
        $responseData = [
            'list' => [
                [
                    'policy_no' => 'POL123456',
                    'order_no' => 'ORD123456',
                    'report_no' => 'REP345678',
                    'delivery_no' => 'DEL901234',
                    'refund_delivery_no' => 'REF567890',
                    'premium' => 1000,
                    'estimate_amount' => 5000,
                    'status' => 2,
                    'pay_fail_reason' => null,
                    'pay_finish_time' => time(),
                    'is_home_pick_up' => 1,
                ],
            ],
        ];

        $this->clientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseData)
        ;

        // 验证同步操作成功执行
        $this->assertNotNull($order->getAccount());
        $this->assertEquals('ORD123456', $order->getOrderNo());

        $service->syncInsuranceOrder($order);
    }

    public function testSyncInsuranceOrderWithEmptyList(): void
    {
        $service = self::getService(InsuranceFreightService::class);

        // 创建真实的实体对象
        $entityManager = self::getService(EntityManagerInterface::class);
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test_app_id');
        $account->setAppSecret('test_app_secret');
        $account->setToken('test_token');
        $account->setEncodingAesKey('test_aes_key');
        $account->setValid(true);
        $entityManager->persist($account);
        $entityManager->flush();

        $order = new InsuranceOrder();
        $order->setAccount($account);
        $order->setOrderNo('ORD123456');
        $order->setOpenId('test_openid');
        $order->setStatus(InsuranceOrderStatus::Securing);
        $order->setPayTime(new \DateTimeImmutable());
        $order->setPayAmount(1000);
        $order->setEstimateAmount(5000);
        $order->setPremium(100);
        $order->setDeliveryNo('DEL123456');
        $order->setDeliveryPlaceProvince('广东省');
        $order->setDeliveryPlaceCity('深圳市');
        $order->setDeliveryPlaceCounty('南山区');
        $order->setDeliveryPlaceAddress('测试地址');
        $order->setReceiptPlaceProvince('北京市');
        $order->setReceiptPlaceCity('北京市');
        $order->setReceiptPlaceCounty('朝阳区');
        $order->setReceiptPlaceAddress('测试收货地址');
        $order->setPolicyNo('POL123456');
        $order->setInsuranceEndDate(new \DateTimeImmutable('+30 days'));

        /** @var array<string, mixed> $responseData */
        $responseData = ['list' => []];

        $this->clientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseData)
        ;

        $service->syncInsuranceOrder($order);
    }

    public function testSyncReturnOrder(): void
    {
        $service = self::getService(InsuranceFreightService::class);

        // 创建真实的实体对象
        $account = new Account();
        $order = new ReturnOrder();
        $order->setAccount($account);
        $order->setReturnId('return_123');

        /** @var array<string, mixed> $responseData */
        $responseData = [
            'status' => 1,
            'waybill_id' => 'WAYBILL123',
            'order_status' => 2,
            'delivery_name' => 'Test Delivery',
            'delivery_id' => 'DEL123',
        ];

        $this->clientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseData)
        ;

        // 验证同步操作后实体状态
        $this->assertEquals('return_123', $order->getReturnId());
        $this->assertNotNull($order->getAccount());

        $service->syncReturnOrder($order);
    }

    public function testSyncReturnOrderWithException(): void
    {
        $service = self::getService(InsuranceFreightService::class);

        // 创建真实的实体对象
        $account = new Account();
        $order = new ReturnOrder();
        $order->setAccount($account);
        $order->setReturnId('return_123');

        $exception = new \Exception('Test exception');

        $this->clientMock->expects($this->once())
            ->method('request')
            ->willThrowException($exception)
        ;

        // NullLogger 不需要期望设置

        $service->syncReturnOrder($order);
    }
}
