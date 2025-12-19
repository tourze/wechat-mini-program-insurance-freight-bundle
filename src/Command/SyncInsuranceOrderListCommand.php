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
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Request\GetInsuranceOrderListRequest;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

#[AsCronTask(expression: '*/15 * * * *')]
#[AsCommand(name: self::NAME, description: '拉取保单信息到本地')]
final class SyncInsuranceOrderListCommand extends LockableCommand
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
        $accounts = $this->accountRepository->findBy(['valid' => true]);

        foreach ($accounts as $account) {
            $this->syncAccountInsuranceOrders($account);
        }

        return Command::SUCCESS;
    }

    private function syncAccountInsuranceOrders(Account $account): void
    {
        $endTime = CarbonImmutable::now();
        $startTime = $endTime->subMonth();
        $limit = 100;
        $offset = 0;

        while (true) {
            $response = $this->fetchInsuranceOrderBatch($account, $startTime, $endTime, $limit, $offset);

            if (!isset($response['list']) || !is_array($response['list']) || [] === $response['list']) {
                break;
            }

            /** @var array<int, mixed> $orderList */
            $orderList = $response['list'];
            $this->processInsuranceOrderBatch($account, $orderList);
            $offset += $limit;
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchInsuranceOrderBatch(Account $account, \DateTimeInterface $startTime, \DateTimeInterface $endTime, int $limit, int $offset): array
    {
        $request = new GetInsuranceOrderListRequest();
        $request->setAccount($account);
        $request->setBeginTime($startTime instanceof CarbonImmutable ? $startTime : CarbonImmutable::instance($startTime));
        $request->setEndTime($endTime instanceof CarbonImmutable ? $endTime : CarbonImmutable::instance($endTime));
        $request->setLimit($limit);
        $request->setOffset($offset);

        $response = $this->client->request($request);

        // 类型检查确保返回数组
        if (!is_array($response)) {
            return [];
        }

        /** @var array<string, mixed> $response */
        return $response;
    }

    /**
     * @param array<int, mixed> $orderItems
     */
    private function processInsuranceOrderBatch(Account $account, array $orderItems): void
    {
        foreach ($orderItems as $item) {
            // 确保 $item 是数组且包含必要的字段
            if (!is_array($item) || !isset($item['policy_no']) || !is_string($item['policy_no'])) {
                continue;
            }

            // 将索引数组转换为字符串键数组以匹配方法签名
            /** @var array<string, mixed> $itemData */
            $itemData = $this->ensureStringKeyedArray($item);

            $order = $this->findOrCreateInsuranceOrder($account, $item['policy_no']);
            $this->insuranceFreightService->overrideOrderInfo($order, $itemData);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }
    }

    /**
     * 确保数组具有字符串键
     *
     * @param array<mixed, mixed> $data
     * @return array<string, mixed>
     */
    private function ensureStringKeyedArray(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[(string) $key] = $value;
        }

        return $result;
    }

    private function findOrCreateInsuranceOrder(Account $account, string $policyNo): InsuranceOrder
    {
        $order = $this->orderRepository->findOneBy(['policyNo' => $policyNo]);

        if (null === $order) {
            $order = new InsuranceOrder();
            $order->setAccount($account);
            $order->setPolicyNo($policyNo);
        }

        return $order;
    }
}
