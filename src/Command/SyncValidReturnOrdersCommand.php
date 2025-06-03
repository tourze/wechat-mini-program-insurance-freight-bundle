<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Carbon\Carbon;
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

#[AsCronTask('*/15 * * * *')]
#[AsCommand(name: SyncValidReturnOrdersCommand::NAME, description: '同步所有有效的退货单信息到本地')]
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
        $orders = $this->orderRepository->createQueryBuilder('a')
            ->where('(a.orderStatus IS NULL OR a.orderStatus NOT IN (:statusList)) AND a.createTime>:minTime')
            ->setParameter('statusList', [
                ReturnOrderStatus::Cancelled,
            ])
            ->setParameter('minTime', Carbon::now()->subDays($_ENV['WECHAT_INSURANCE_SYNC_RETURN_ORDER_DAY_NUM'] ?? 15))
            ->getQuery()
            ->toIterable();

        foreach ($orders as $order) {
            /* @var ReturnOrder $order */
            $output->writeln("开始异步检查：{$order->getId()}");

            $message = new RunCommandMessage();
            $message->setCommand(SyncSingleReturnOrderCommand::NAME);
            $message->setOptions(['shopOrderId' => $order->getShopOrderId()]);
            $this->messageBus->dispatch($message);

            $this->entityManager->detach($order);
        }

        return Command::SUCCESS;
    }
}
