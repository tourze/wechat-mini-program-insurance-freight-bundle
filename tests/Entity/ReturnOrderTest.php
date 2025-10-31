<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;

/**
 * @internal
 */
#[CoversClass(ReturnOrder::class)]
final class ReturnOrderTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ReturnOrder();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'bizAddress' => ['bizAddress', ['key' => 'value']],
            'userAddress' => ['userAddress', ['key' => 'value']],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testReturnOrderSpecificSetters(): void
    {
        $returnOrder = new ReturnOrder();

        $returnId = 'R2021123456789';
        $returnOrder->setReturnId($returnId);
        $this->assertEquals($returnId, $returnOrder->getReturnId());

        // Mock具体类说明: WechatMiniProgramBundle\Entity\Account是数据实体类，
        // 没有对应的接口定义，测试中需要模拟其行为来验证业务逻辑。
        // 使用具体类Mock是合理的，因为Entity类主要包含数据属性和简单的getter/setter方法。
        // 替代方案：可以考虑创建测试专用的Entity工厂类，但当前Mock方式更直观简洁。
        $account = $this->createMock(Account::class);
        $returnOrder->setAccount($account);
        $this->assertSame($account, $returnOrder->getAccount());

        $status = ReturnStatus::Appointment;
        $returnOrder->setStatus($status);
        $this->assertSame($status, $returnOrder->getStatus());

        $orderStatus = ReturnOrderStatus::Ordered;
        $returnOrder->setOrderStatus($orderStatus);
        $this->assertSame($orderStatus, $returnOrder->getOrderStatus());

        $waybillId = 'SF1234567890';
        $returnOrder->setWaybillId($waybillId);
        $this->assertEquals($waybillId, $returnOrder->getWaybillId());

        $deliveryId = 'SF';
        $returnOrder->setDeliveryId($deliveryId);
        $this->assertEquals($deliveryId, $returnOrder->getDeliveryId());

        $deliveryName = '顺丰速运';
        $returnOrder->setDeliveryName($deliveryName);
        $this->assertEquals($deliveryName, $returnOrder->getDeliveryName());

        $createTime = new \DateTimeImmutable('2023-01-01 10:00:00');
        $returnOrder->setCreateTime($createTime);
        $this->assertSame($createTime, $returnOrder->getCreateTime());

        $updateTime = new \DateTimeImmutable('2023-01-02 11:00:00');
        $returnOrder->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $returnOrder->getUpdateTime());

        $shopOrderId = 'SHOP2021123456789';
        $returnOrder->setShopOrderId($shopOrderId);
        $this->assertEquals($shopOrderId, $returnOrder->getShopOrderId());

        $openId = 'o1234567890abcdef';
        $returnOrder->setOpenId($openId);
        $this->assertEquals($openId, $returnOrder->getOpenId());

        $orderPath = 'pages/order/detail?id=123';
        $returnOrder->setOrderPath($orderPath);
        $this->assertEquals($orderPath, $returnOrder->getOrderPath());

        $goodsList = [
            ['id' => 1, 'name' => '商品1', 'price' => 100],
            ['id' => 2, 'name' => '商品2', 'price' => 200],
        ];
        $returnOrder->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $returnOrder->getGoodsList());

        $wxPayId = '4200000001202107010123456789';
        $returnOrder->setWxPayId($wxPayId);
        $this->assertEquals($wxPayId, $returnOrder->getWxPayId());
    }
}
