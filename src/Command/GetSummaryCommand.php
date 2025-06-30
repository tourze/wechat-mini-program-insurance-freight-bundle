<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;
use WechatMiniProgramInsuranceFreightBundle\Repository\SummaryRepository;
use WechatMiniProgramInsuranceFreightBundle\Request\GetSummaryRequest;

#[AsCronTask(expression: '30 */6 * * *')]
#[AsCommand(name: self::NAME, description: '拉取摘要接口')]
class GetSummaryCommand extends LockableCommand
{
    public const NAME = 'wechat-insurance:get-summary';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly SummaryRepository $summaryRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $endTime = CarbonImmutable::today()->endOfDay();
        $startTime = $endTime->subDays(3);
        $dateList = CarbonPeriod::between($startTime, $endTime);

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            /** @var \Carbon\CarbonImmutable $date */
            foreach ($dateList->toArray() as $date) {
                $request = new GetSummaryRequest();
                $request->setAccount($account);
                $request->setBeginTime($date->clone()->startOfDay());
                $request->setEndTime($date->clone()->endOfDay());
                $response = $this->client->request($request);

                $summary = $this->summaryRepository->findOneBy([
                    'account' => $account,
                    'date' => $date->startOfDay(),
                ]);
                if ($summary === null) {
                    $summary = new Summary();
                    $summary->setAccount($account);
                    $summary->setDate($date->startOfDay());
                }

                $summary->setTotal($response['total']);
                $summary->setClaimNum($response['claim_num']);
                $summary->setClaimSuccessNum($response['claim_succ_num']);
                $summary->setPremium($response['premium']);
                $summary->setFunds($response['funds']);
                $summary->setNeedClose((bool) $response['need_close']);
                $this->entityManager->persist($summary);
                $this->entityManager->flush();
            }
        }

        return Command::SUCCESS;
    }
}
