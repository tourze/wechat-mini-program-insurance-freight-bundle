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
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;
use WechatMiniProgramInsuranceFreightBundle\Repository\SummaryRepository;
use WechatMiniProgramInsuranceFreightBundle\Request\GetSummaryRequest;

#[AsCronTask(expression: '30 */6 * * *')]
#[AsCommand(name: self::NAME, description: '拉取摘要接口')]
final class GetSummaryCommand extends LockableCommand
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
            $this->processSummaryForAccount($account, $dateList);
        }

        return Command::SUCCESS;
    }

    private function processSummaryForAccount(Account $account, CarbonPeriod $dateList): void
    {
        /** @var CarbonImmutable $date */
        foreach ($dateList->toArray() as $date) {
            $response = $this->fetchSummaryResponse($account, $date);

            if (!is_array($response)) {
                continue;
            }

            $summary = $this->findOrCreateSummary($account, $date);
            /** @var array<string, mixed> $response */
            $this->updateSummaryFromResponse($summary, $response);

            $this->entityManager->persist($summary);
            $this->entityManager->flush();
        }
    }

    private function fetchSummaryResponse(Account $account, CarbonImmutable $date): mixed
    {
        $request = new GetSummaryRequest();
        $request->setAccount($account);
        $request->setBeginTime($date->clone()->startOfDay());
        $request->setEndTime($date->clone()->endOfDay());

        return $this->client->request($request);
    }

    private function findOrCreateSummary(Account $account, CarbonImmutable $date): Summary
    {
        $summary = $this->summaryRepository->findOneBy([
            'account' => $account,
            'date' => $date->startOfDay(),
        ]);

        if (!$summary instanceof Summary) {
            $summary = new Summary();
            $summary->setAccount($account);
            $summary->setDate($date->startOfDay());
        }

        return $summary;
    }

    /**
     * @param array<string, mixed> $response
     */
    private function updateSummaryFromResponse(Summary $summary, array $response): void
    {
        $summary->setTotal($this->safeIntCast($response['total'] ?? 0));
        $summary->setClaimNum($this->safeIntCast($response['claim_num'] ?? 0));
        $summary->setClaimSuccessNum($this->safeIntCast($response['claim_succ_num'] ?? 0));
        $summary->setPremium($this->safeIntCast($response['premium'] ?? 0));
        $summary->setFunds($this->safeIntCast($response['funds'] ?? 0));
        $summary->setNeedClose((bool) ($response['need_close'] ?? false));
    }

    private function safeIntCast(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return 0;
    }
}
