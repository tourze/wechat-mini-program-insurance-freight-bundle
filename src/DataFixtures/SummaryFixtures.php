<?php

namespace WechatMiniProgramInsuranceFreightBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;

#[When(env: 'test')]
#[When(env: 'dev')]
class SummaryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setName('保险运费测试小程序');
        $account->setValid(true);
        $account->setAppId('wx_insurance_test_001');
        $account->setAppSecret('insurance_secret_001');
        $manager->persist($account);

        $today = new \DateTimeImmutable('today');
        $yesterday = new \DateTimeImmutable('yesterday');
        $weekAgo = new \DateTimeImmutable('-7 days');
        $monthAgo = new \DateTimeImmutable('-30 days');

        // 今日数据
        $summary = new Summary();
        $summary->setAccount($account);
        $summary->setDate($today);
        $summary->setTotal(150);
        $summary->setClaimNum(12);
        $summary->setClaimSuccessNum(10);
        $summary->setPremium(3000);
        $summary->setFunds(985000);
        $summary->setNeedClose(false);
        $manager->persist($summary);

        // 昨日数据
        $summary = new Summary();
        $summary->setAccount($account);
        $summary->setDate($yesterday);
        $summary->setTotal(138);
        $summary->setClaimNum(15);
        $summary->setClaimSuccessNum(13);
        $summary->setPremium(2760);
        $summary->setFunds(982000);
        $summary->setNeedClose(false);
        $manager->persist($summary);

        // 一周前数据
        $summary = new Summary();
        $summary->setAccount($account);
        $summary->setDate($weekAgo);
        $summary->setTotal(165);
        $summary->setClaimNum(20);
        $summary->setClaimSuccessNum(18);
        $summary->setPremium(3300);
        $summary->setFunds(970000);
        $summary->setNeedClose(false);
        $manager->persist($summary);

        // 一个月前数据（余额不足，需要关闭）
        $summary = new Summary();
        $summary->setAccount($account);
        $summary->setDate($monthAgo);
        $summary->setTotal(200);
        $summary->setClaimNum(25);
        $summary->setClaimSuccessNum(20);
        $summary->setPremium(4000);
        $summary->setFunds(8000);
        $summary->setNeedClose(true);
        $manager->persist($summary);

        // 生成过去30天的统计数据
        for ($i = 2; $i <= 29; ++$i) {
            $date = new \DateTimeImmutable("-{$i} days");
            // 跳过已经创建的日期
            if (7 === $i) {
                continue;
            }

            $summary = new Summary();
            $summary->setAccount($account);
            $summary->setDate($date);
            $summary->setTotal(mt_rand(120, 180));
            $summary->setClaimNum(mt_rand(10, 25));
            $claimNum = $summary->getClaimNum();
            $minSuccessNum = null !== $claimNum ? (int) ($claimNum * 0.7) : 0;
            $maxSuccessNum = $claimNum ?? 0;
            $summary->setClaimSuccessNum(mt_rand($minSuccessNum, $maxSuccessNum));
            $summary->setPremium(mt_rand(2400, 3600));
            $summary->setFunds(mt_rand(900000, 990000));
            $summary->setNeedClose(false);
            $manager->persist($summary);
        }

        $manager->flush();
    }
}
