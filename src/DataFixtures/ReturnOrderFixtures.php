<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramBundle\DataFixtures\AccountFixtures;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;

#[When(env: 'test')]
#[When(env: 'dev')]
class ReturnOrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $account = $this->getReference(AccountFixtures::class, Account::class);

        $returnOrder = new ReturnOrder();
        $returnOrder->setAccount($account);
        $returnOrder->setShopOrderId('TEST_RETURN_' . (string) (time() + random_int(1, 999)));
        $returnOrder->setOpenId('test_open_id_002');
        $returnOrder->setWxPayId('WX_PAY_TEST_' . (string) (time() + random_int(100, 999)));
        $returnOrder->setWaybillId('WAYBILL_' . (string) (time() + random_int(1000, 9999)));
        $returnOrder->setStatus(ReturnStatus::Waiting);
        $returnOrder->setOrderStatus(ReturnOrderStatus::Ordered);
        $returnOrder->setDeliveryName('顺丰速运');
        $returnOrder->setDeliveryId('SF');
        $returnOrder->setReturnId('RETURN_' . (string) (time() + random_int(100, 999)));
        $returnOrder->setReportNo('REPORT_' . (string) (time() + random_int(1000, 9999)));
        $returnOrder->setBizAddress([
            'province' => '广东省',
            'city' => '深圳市',
            'district' => '南山区',
            'address' => '科技园北区',
            'name' => '商家',
            'phone' => '13800138000',
        ]);
        $returnOrder->setUserAddress([
            'province' => '北京市',
            'city' => '北京市',
            'district' => '朝阳区',
            'address' => '建国路88号',
            'name' => '用户',
            'phone' => '13900139000',
        ]);
        $returnOrder->setGoodsList([
            [
                'name' => '测试商品',
                'price' => 10000,
                'quantity' => 1,
                'sku' => 'TEST-SKU-001',
            ],
        ]);

        $manager->persist($returnOrder);
        $manager->flush();

        $this->addReference(self::class, $returnOrder);
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class,
        ];
    }
}
