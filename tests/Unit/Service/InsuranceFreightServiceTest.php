<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Request\GetInsuranceOrderListRequest;
use WechatMiniProgramInsuranceFreightBundle\Request\GetReturnOrderRequest;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

class InsuranceFreightServiceTest extends TestCase
{
    private Client|MockObject $client;
    private InsuranceOrderRepository|MockObject $orderRepository;
    private ReturnOrderRepository|MockObject $returnOrderRepository;
    private LoggerInterface|MockObject $logger;
    private EntityManagerInterface|MockObject $entityManager;
    private InsuranceFreightService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createMock(Client::class);
        $this->orderRepository = $this->createMock(InsuranceOrderRepository::class);
        $this->returnOrderRepository = $this->createMock(ReturnOrderRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->service = new InsuranceFreightService(
            $this->client,
            $this->orderRepository,
            $this->returnOrderRepository,
            $this->logger,
            $this->entityManager
        );
    }

    public function testOverrideOrderInfo_withValidData(): void
    {
        // 创建测试数据
        $order = new InsuranceOrder();
        $item = [
            'policy_no' => 'P202112345678',
            'order_no' => '2021123456789',
            'report_no' => 'R202101010001',
            'delivery_no' => 'SF1234567890',
            'refund_delivery_no' => 'SF0987654321',
            'premium' => 100,
            'estimate_amount' => 2000,
            'status' => InsuranceOrderStatus::Securing->value,
            'pay_fail_reason' => '银行卡信息有误',
            'pay_finish_time' => time(),
            'is_home_pick_up' => true,
        ];

        // 执行测试方法
        $this->service->overrideOrderInfo($order, $item);

        // 验证结果
        $this->assertEquals('P202112345678', $order->getPolicyNo());
        $this->assertEquals('2021123456789', $order->getOrderNo());
        $this->assertEquals('R202101010001', $order->getReportNo());
        $this->assertEquals('SF1234567890', $order->getDeliveryNo());
        $this->assertEquals('SF0987654321', $order->getRefundDeliveryNo());
        $this->assertEquals(100, $order->getPremium());
        $this->assertEquals(2000, $order->getEstimateAmount());
        $this->assertEquals(InsuranceOrderStatus::Securing, $order->getStatus());
        $this->assertEquals('银行卡信息有误', $order->getPayFailReason());
        $this->assertNotNull($order->getPayFinishTime());
        $this->assertTrue($order->isHomePickUp());
    }

    public function testOverrideOrderInfo_withNullValues(): void
    {
        // 创建测试数据
        $order = new InsuranceOrder();
        $item = [
            'policy_no' => 'P202112345678',
            'order_no' => '2021123456789',
            'report_no' => 'R202101010001',
            'delivery_no' => 'SF1234567890',
            'refund_delivery_no' => 'SF0987654321',
            'premium' => 100,
            'estimate_amount' => 2000,
            'status' => InsuranceOrderStatus::Securing->value,
            'is_home_pick_up' => false,
        ];

        // 执行测试方法
        $this->service->overrideOrderInfo($order, $item);

        // 验证结果
        $this->assertEquals('P202112345678', $order->getPolicyNo());
        $this->assertNull($order->getPayFailReason());
        $this->assertNull($order->getPayFinishTime());
        $this->assertFalse($order->isHomePickUp());
    }

    public function testSyncInsuranceOrder_withValidResponse(): void
    {
        // 创建测试数据
        $account = $this->createMock(Account::class);
        $order = new InsuranceOrder();
        $order->setAccount($account);
        $order->setOrderNo('2021123456789');

        $responseData = [
            'list' => [
                [
                    'policy_no' => 'P202112345678',
                    'order_no' => '2021123456789',
                    'report_no' => 'R202101010001',
                    'delivery_no' => 'SF1234567890',
                    'refund_delivery_no' => 'SF0987654321',
                    'premium' => 100,
                    'estimate_amount' => 2000,
                    'status' => InsuranceOrderStatus::Securing->value,
                    'pay_fail_reason' => null,
                    'pay_finish_time' => null,
                    'is_home_pick_up' => false,
                ]
            ]
        ];

        // 设置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->with($this->callback(function (GetInsuranceOrderListRequest $request) use ($account) {
                $this->assertSame($account, $request->getAccount());
                $this->assertEquals('2021123456789', $request->getOrderNo());
                $this->assertEquals(1, $request->getLimit());
                $this->assertEquals(0, $request->getOffset());
                return true;
            }))
            ->willReturn($responseData);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($order);

        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试方法
        $this->service->syncInsuranceOrder($order);

        // 验证结果
        $this->assertEquals('P202112345678', $order->getPolicyNo());
        $this->assertEquals('2021123456789', $order->getOrderNo());
        $this->assertEquals('R202101010001', $order->getReportNo());
    }

    public function testSyncInsuranceOrder_withEmptyResponse(): void
    {
        // 创建测试数据
        $account = $this->createMock(Account::class);
        $order = new InsuranceOrder();
        $order->setAccount($account);
        $order->setOrderNo('2021123456789');

        $responseData = [
            'list' => []
        ];

        // 设置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->entityManager->expects($this->never())
            ->method('persist');

        $this->entityManager->expects($this->never())
            ->method('flush');

        // 执行测试方法
        $this->service->syncInsuranceOrder($order);
    }

    public function testSyncReturnOrder_withValidResponse(): void
    {
        // 创建测试数据
        $account = $this->createMock(Account::class);
        $returnOrder = new ReturnOrder();
        $returnOrder->setAccount($account);
        $returnOrder->setReturnId('R123456');

        $responseData = [
            'status' => ReturnStatus::Appointment->value,
            'waybill_id' => 'SF1234567890',
            'order_status' => ReturnOrderStatus::Ordered->value,
            'delivery_name' => '顺丰速运',
            'delivery_id' => 'SF',
        ];

        // 设置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->with($this->callback(function (GetReturnOrderRequest $request) use ($account) {
                $this->assertSame($account, $request->getAccount());
                $this->assertEquals('R123456', $request->getReturnId());
                return true;
            }))
            ->willReturn($responseData);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($returnOrder);

        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试方法
        $this->service->syncReturnOrder($returnOrder);

        // 验证结果
        $this->assertEquals(ReturnStatus::Appointment, $returnOrder->getStatus());
        $this->assertEquals('SF1234567890', $returnOrder->getWaybillId());
        $this->assertEquals(ReturnOrderStatus::Ordered, $returnOrder->getOrderStatus());
        $this->assertEquals('顺丰速运', $returnOrder->getDeliveryName());
        $this->assertEquals('SF', $returnOrder->getDeliveryId());
    }

    public function testSyncReturnOrder_withException(): void
    {
        // 创建测试数据
        $account = $this->createMock(Account::class);
        $returnOrder = new ReturnOrder();
        $returnOrder->setAccount($account);
        $returnOrder->setReturnId('R123456');

        // 设置模拟行为，抛出异常
        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API请求失败'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with('同步退货单信息失败', $this->callback(function ($context) {
                $this->assertArrayHasKey('exception', $context);
                $this->assertArrayHasKey('order', $context);
                $this->assertInstanceOf(\Exception::class, $context['exception']);
                $this->assertInstanceOf(ReturnOrder::class, $context['order']);
                return true;
            }));

        $this->entityManager->expects($this->never())
            ->method('persist');

        $this->entityManager->expects($this->never())
            ->method('flush');

        // 执行测试方法
        $this->service->syncReturnOrder($returnOrder);
    }
}
