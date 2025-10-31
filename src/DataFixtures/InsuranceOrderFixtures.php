<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramBundle\DataFixtures\AccountFixtures;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

#[When(env: 'test')]
#[When(env: 'dev')]
class InsuranceOrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $account = $this->getReference(AccountFixtures::class, Account::class);

        $order = new InsuranceOrder();
        $order->setAccount($account);
        $order->setOpenId('test_open_id_001');
        $order->setOrderNo('TEST_ORDER_' . (string) (time() + random_int(1, 999)));
        $order->setStatus(InsuranceOrderStatus::Securing);
        $order->setPayTime(new \DateTimeImmutable('2024-01-01 10:00:00'));
        $order->setPayAmount(10000);
        $order->setEstimateAmount(9000);
        $order->setPremium(100);
        $order->setDeliveryNo('SF1234567890');
        $order->setDeliveryPlaceProvince('广东省');
        $order->setDeliveryPlaceCity('深圳市');
        $order->setDeliveryPlaceCounty('南山区');
        $order->setDeliveryPlaceAddress('科技园北区');
        $order->setReceiptPlaceProvince('北京市');
        $order->setReceiptPlaceCity('北京市');
        $order->setReceiptPlaceCounty('朝阳区');
        $order->setReceiptPlaceAddress('建国路88号');
        $order->setPolicyNo('POLICY_' . (string) (time() + random_int(100, 999)));
        $order->setInsuranceEndDate(new \DateTimeImmutable('+30 days'));

        $manager->persist($order);
        $manager->flush();

        $this->addReference(self::class, $order);
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class,
        ];
    }
}
