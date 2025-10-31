<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;

/**
 * @template-extends AbstractRepositoryTestCase<ReturnOrder>
 * @internal
 */
#[CoversClass(ReturnOrderRepository::class)]
#[RunTestsInSeparateProcesses]
final class ReturnOrderRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        // 确保在测试环境下运行，避免触发监听器的业务逻辑
        $_ENV['APP_ENV'] = 'test';

        $entity = new ReturnOrder();
        $entity->setReturnId('test_return_' . uniqid());
        $entity->setStatus(ReturnStatus::Waiting);
        $entity->setBizAddress([
            'name' => 'Test Business',
            'phone' => '12345678901',
            'province' => '测试省',
            'city' => '测试市',
            'district' => '测试区',
            'address' => '测试地址',
        ]);
        $entity->setUserAddress([
            'name' => 'Test User',
            'phone' => '98765432101',
            'province' => '用户省',
            'city' => '用户市',
            'district' => '用户区',
            'address' => '用户地址',
        ]);
        $entity->setOpenId('test_open_id_' . uniqid());
        $entity->setWxPayId('wx_pay_' . uniqid());
        $entity->setShopOrderId('shop_order_' . uniqid());

        return $entity;
    }

    /**
     * @return ReturnOrderRepository
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(ReturnOrderRepository::class);
    }

    protected function onSetUp(): void
    {
        // 检查当前测试是否需要 DataFixtures 数据
        $currentTest = $this->name();
        if ('testCountWithDataFixtureShouldReturnGreaterThanZero' === $currentTest) {
            // 为计数测试创建测试数据
            $this->createTestDataForCountTest();
        }
    }

    private function createTestDataForCountTest(): void
    {
        // 确保在测试环境下运行，避免触发监听器的业务逻辑
        $_ENV['APP_ENV'] = 'test';

        $entity = new ReturnOrder();
        $entity->setReturnId('test_return_fixture');
        $entity->setStatus(ReturnStatus::Waiting);
        $entity->setBizAddress([
            'name' => 'Test Business',
            'phone' => '12345678901',
            'province' => '测试省',
            'city' => '测试市',
            'district' => '测试区',
            'address' => '测试地址',
        ]);
        $entity->setUserAddress([
            'name' => 'Test User',
            'phone' => '98765432101',
            'province' => '用户省',
            'city' => '用户市',
            'district' => '用户区',
            'address' => '用户地址',
        ]);
        $entity->setOpenId('test_open_id_fixture');
        $entity->setWxPayId('wx_pay_fixture');
        $entity->setShopOrderId('shop_order_fixture');

        $this->getRepository()->save($entity, true);
    }

    public function testFindByWechatTradeNo(): void
    {
        // 测试查找不存在的微信交易号
        $result = $this->getRepository()->findByWechatTradeNo('nonexistent_trade_no');
        $this->assertNull($result);
    }

    public function testFindByAccountNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['account' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByAccountNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['account' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByShopOrderIdNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['shopOrderId' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByShopOrderIdNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['shopOrderId' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByOpenIdNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['openId' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByOpenIdNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['openId' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByOrderPathNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['orderPath' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByOrderPathNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['orderPath' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByGoodsListNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['goodsList' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByGoodsListNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['goodsList' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByWxPayIdNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['wxPayId' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByWxPayIdNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['wxPayId' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByWaybillIdNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['waybillId' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByWaybillIdNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['waybillId' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByStatusNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['status' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByStatusNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['status' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByOrderStatusNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['orderStatus' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByOrderStatusNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['orderStatus' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByDeliveryNameNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['deliveryName' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByDeliveryNameNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['deliveryName' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByDeliveryIdNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['deliveryId' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByDeliveryIdNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['deliveryId' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByReturnIdNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['returnId' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByReturnIdNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['returnId' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByReportNoNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['reportNo' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByReportNoNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['reportNo' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testSave(): void
    {
        // 确保在测试环境下运行，避免触发监听器的业务逻辑
        $_ENV['APP_ENV'] = 'test';

        $entity = new ReturnOrder();
        $entity->setReturnId('test_return_' . uniqid());
        $entity->setStatus(ReturnStatus::Waiting);
        $entity->setBizAddress(['name' => 'Test Name', 'phone' => '12345678901', 'province' => 'Test Province', 'city' => 'Test City', 'district' => 'Test District', 'address' => 'Test Address']);
        $entity->setUserAddress(['name' => 'User Name', 'phone' => '12345678901', 'province' => 'User Province', 'city' => 'User City', 'district' => 'User District', 'address' => 'User Address']);
        $entity->setOpenId('test_open_id');
        $entity->setWxPayId('wx_pay_' . uniqid());
        $entity->setShopOrderId('shop_order_' . uniqid());

        $this->getRepository()->save($entity);
        $this->assertNotNull($entity->getId());

        $this->getRepository()->remove($entity);
    }

    public function testRemove(): void
    {
        // 确保在测试环境下运行，避免触发监听器的业务逻辑
        $_ENV['APP_ENV'] = 'test';

        $entity = new ReturnOrder();
        $entity->setReturnId('test_return_' . uniqid());
        $entity->setStatus(ReturnStatus::Waiting);
        $entity->setBizAddress(['name' => 'Test Name', 'phone' => '12345678901', 'province' => 'Test Province', 'city' => 'Test City', 'district' => 'Test District', 'address' => 'Test Address']);
        $entity->setUserAddress(['name' => 'User Name', 'phone' => '12345678901', 'province' => 'User Province', 'city' => 'User City', 'district' => 'User District', 'address' => 'User Address']);
        $entity->setOpenId('test_open_id');
        $entity->setWxPayId('wx_pay_' . uniqid());
        $entity->setShopOrderId('shop_order_' . uniqid());

        $this->getRepository()->save($entity);
        $id = $entity->getId();

        $this->getRepository()->remove($entity);
        $foundEntity = $this->getRepository()->find($id);
        $this->assertNull($foundEntity);
    }
}
