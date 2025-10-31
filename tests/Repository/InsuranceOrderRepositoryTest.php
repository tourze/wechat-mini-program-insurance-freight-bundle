<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;

/**
 * @template-extends AbstractRepositoryTestCase<InsuranceOrder>
 * @internal
 */
#[CoversClass(InsuranceOrderRepository::class)]
#[RunTestsInSeparateProcesses]
final class InsuranceOrderRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        // 确保在测试环境下运行，避免触发监听器的业务逻辑
        $_ENV['APP_ENV'] = 'test';

        $entity = new InsuranceOrder();
        $entity->setOpenId('test_open_id_' . uniqid());
        $entity->setOrderNo('test_order_' . uniqid());
        $entity->setStatus(InsuranceOrderStatus::Securing);
        $entity->setPayTime(new \DateTimeImmutable());
        $entity->setPayAmount(1000);
        $entity->setEstimateAmount(1000);
        $entity->setPremium(50);
        $entity->setDeliveryNo('test_delivery_' . uniqid());
        $entity->setDeliveryPlaceProvince('测试省');
        $entity->setDeliveryPlaceCity('测试市');
        $entity->setDeliveryPlaceCounty('测试区');
        $entity->setDeliveryPlaceAddress('测试地址');
        $entity->setReceiptPlaceProvince('收货省');
        $entity->setReceiptPlaceCity('收货市');
        $entity->setReceiptPlaceCounty('收货区');
        $entity->setReceiptPlaceAddress('收货地址');
        $entity->setPolicyNo('test_policy_' . uniqid());
        $entity->setInsuranceEndDate(new \DateTimeImmutable('+1 year'));

        return $entity;
    }

    /**
     * @return InsuranceOrderRepository
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(InsuranceOrderRepository::class);
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

        $entity = new InsuranceOrder();
        $entity->setOpenId('test_open_id_fixture');
        $entity->setOrderNo('test_order_fixture');
        $entity->setStatus(InsuranceOrderStatus::Securing);
        $entity->setPayTime(new \DateTimeImmutable());
        $entity->setPayAmount(1000);
        $entity->setEstimateAmount(1000);
        $entity->setPremium(50);
        $entity->setDeliveryNo('test_delivery_fixture');
        $entity->setDeliveryPlaceProvince('测试省');
        $entity->setDeliveryPlaceCity('测试市');
        $entity->setDeliveryPlaceCounty('测试区');
        $entity->setDeliveryPlaceAddress('测试地址');
        $entity->setReceiptPlaceProvince('收货省');
        $entity->setReceiptPlaceCity('收货市');
        $entity->setReceiptPlaceCounty('收货区');
        $entity->setReceiptPlaceAddress('收货地址');
        $entity->setPolicyNo('test_policy_fixture');
        $entity->setInsuranceEndDate(new \DateTimeImmutable('+1 year'));

        $this->getRepository()->save($entity, true);
    }

    public function testFindByWechatTradeNo(): void
    {
        $result = $this->getRepository()->findByWechatTradeNo('non_existent_trade_no');
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

    public function testFindByRefundDeliveryNoNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['refundDeliveryNo' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByRefundDeliveryNoNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['refundDeliveryNo' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByRefundCompanyNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['refundCompany' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByRefundCompanyNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['refundCompany' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByPayFailReasonNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['payFailReason' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByPayFailReasonNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['payFailReason' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByPayFinishTimeNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['payFinishTime' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByPayFinishTimeNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['payFinishTime' => null]);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testFindByHomePickUpNull(): void
    {
        self::getEntityManager()->clear();
        $results = $this->getRepository()->findBy(['homePickUp' => null]);
        $this->assertIsArray($results);
    }

    public function testCountByHomePickUpNull(): void
    {
        self::getEntityManager()->clear();
        $count = $this->getRepository()->count(['homePickUp' => null]);
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

        $entity = new InsuranceOrder();
        $entity->setOpenId('test_open_id');
        $entity->setOrderNo('test_order_' . uniqid());
        $entity->setStatus(InsuranceOrderStatus::Securing);
        $entity->setPayTime(new \DateTimeImmutable());
        $entity->setPayAmount(1000);
        $entity->setEstimateAmount(1000);
        $entity->setPremium(50);
        $entity->setDeliveryNo('test_delivery_no');
        $entity->setDeliveryPlaceProvince('测试省');
        $entity->setDeliveryPlaceCity('测试市');
        $entity->setDeliveryPlaceCounty('测试区');
        $entity->setDeliveryPlaceAddress('测试地址');
        $entity->setReceiptPlaceProvince('收货省');
        $entity->setReceiptPlaceCity('收货市');
        $entity->setReceiptPlaceCounty('收货区');
        $entity->setReceiptPlaceAddress('收货地址');
        $entity->setPolicyNo('test_policy_no');
        $entity->setInsuranceEndDate(new \DateTimeImmutable('+1 year'));
        $entity->setOrderPath('/pages/test');
        $entity->setGoodsList([['name' => 'test product', 'price' => 100, 'url' => 'https://example.com/product']]);

        $this->getRepository()->save($entity);
        $this->assertNotNull($entity->getId());

        $this->getRepository()->remove($entity);
    }

    public function testRemove(): void
    {
        // 确保在测试环境下运行，避免触发监听器的业务逻辑
        $_ENV['APP_ENV'] = 'test';

        $entity = new InsuranceOrder();
        $entity->setOpenId('test_open_id');
        $entity->setOrderNo('test_order_' . uniqid());
        $entity->setStatus(InsuranceOrderStatus::Securing);
        $entity->setPayTime(new \DateTimeImmutable());
        $entity->setPayAmount(1000);
        $entity->setEstimateAmount(1000);
        $entity->setPremium(50);
        $entity->setDeliveryNo('test_delivery_no');
        $entity->setDeliveryPlaceProvince('测试省');
        $entity->setDeliveryPlaceCity('测试市');
        $entity->setDeliveryPlaceCounty('测试区');
        $entity->setDeliveryPlaceAddress('测试地址');
        $entity->setReceiptPlaceProvince('收货省');
        $entity->setReceiptPlaceCity('收货市');
        $entity->setReceiptPlaceCounty('收货区');
        $entity->setReceiptPlaceAddress('收货地址');
        $entity->setPolicyNo('test_policy_no');
        $entity->setInsuranceEndDate(new \DateTimeImmutable('+1 year'));
        $entity->setOrderPath('/pages/test');
        $entity->setGoodsList([['name' => 'test product', 'price' => 100, 'url' => 'https://example.com/product']]);

        $this->getRepository()->save($entity);
        $id = $entity->getId();

        $this->getRepository()->remove($entity);
        $foundEntity = $this->getRepository()->find($id);
        $this->assertNull($foundEntity);
    }
}
