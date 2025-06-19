<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Request\GetInsuranceOrderListRequest;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

#[AsCronTask('*/15 * * * *')]
#[AsCommand(name: self::NAME, description: '拉取保单信息到本地')]
class SyncInsuranceOrderListCommand extends LockableCommand
{
    public const NAME = 'wechat-insurance:sync-insurance-order-list';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly InsuranceOrderRepository $orderRepository,
        private readonly Client $client,
        private readonly InsuranceFreightService $insuranceFreightService,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            // 拉最近一个月的数据
            $endTime = CarbonImmutable::now();
            $startTime = $endTime->subMonth();

            $limit = 100;
            $offset = 0;

            while (true) {
                $request = new GetInsuranceOrderListRequest();
                $request->setAccount($account);
                $request->setBeginTime($startTime);
                $request->setEndTime($endTime);
                $request->setLimit($limit);
                $request->setOffset($offset);
                $response = $this->client->request($request);

                if ((bool) empty($response['list'])) {
                    break;
                }
                foreach ($response['list'] as $item) {
                    $order = $this->orderRepository->findOneBy([
                        'policyNo' => $item['policy_no'],
                    ]);
                    if ($order === null) {
                        $order = new InsuranceOrder();
                        $order->setAccount($account);
                        $order->setPolicyNo($item['policy_no']);
                    }
                    $this->insuranceFreightService->overrideOrderInfo($order, $item);
                    $this->entityManager->persist($order);
                    $this->entityManager->flush();
                }

                $offset += $limit;
            }
        }

        return Command::SUCCESS;
    }
}
