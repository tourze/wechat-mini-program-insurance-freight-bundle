<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Tourze\AsyncCommandBundle\Message\RunCommandMessage;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;

#[AsCronTask(expression: '*/15 * * * *')]
#[AsCommand(name: self::NAME, description: '同步所有有效的退货单信息到本地')]
class SyncValidReturnOrdersCommand extends LockableCommand
{
    public const NAME = 'wechat-insurance:sync-valid-return-orders';

    public function __construct(
        private readonly ReturnOrderRepository $orderRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dayCount = $this->getSyncReturnOrderDayCount();

        $orders = $this->orderRepository->createQueryBuilder('a')
            ->where('(a.orderStatus IS NULL OR a.orderStatus NOT IN (:statusList)) AND a.createTime>:minTime')
            ->setParameter('statusList', [
                ReturnOrderStatus::Cancelled,
            ])
            ->setParameter('minTime', CarbonImmutable::now()->subDays($dayCount))
            ->getQuery()
            ->toIterable()
        ;

        foreach ($orders as $order) {
            if (!$order instanceof ReturnOrder) {
                continue;
            }

            $output->writeln("开始异步检查：{$order->getId()}");

            $message = new RunCommandMessage();
            $message->setCommand(SyncSingleReturnOrderCommand::NAME);
            $message->setOptions(['shopOrderId' => $order->getShopOrderId()]);
            $this->messageBus->dispatch($message);

            $this->entityManager->detach($order);
        }

        return Command::SUCCESS;
    }

    private function getSyncReturnOrderDayCount(): int
    {
        $envValue = $_ENV['WECHAT_INSURANCE_SYNC_RETURN_ORDER_DAY_NUM'] ?? '15';

        if (is_int($envValue)) {
            return $envValue;
        }

        if (is_string($envValue) && is_numeric($envValue)) {
            return (int) $envValue;
        }

        return 15; // 默认值
    }
}
