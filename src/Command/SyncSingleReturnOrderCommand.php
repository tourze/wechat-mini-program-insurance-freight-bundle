<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

#[AsCommand(name: SyncSingleReturnOrderCommand::NAME, description: '同步单个退货信息到本地')]
class SyncSingleReturnOrderCommand extends LockableCommand
{
    public const NAME = 'wechat-insurance:sync-single-return-order';

    public function __construct(
        private readonly ReturnOrderRepository $orderRepository,
        private readonly InsuranceFreightService $insuranceFreightService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('同步单个退货信息到本地')
            ->addArgument('shopOrderId', InputArgument::REQUIRED, '商家内部系统使用的退货编号');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $order = $this->orderRepository->findOneBy([
            'shopOrderId' => $input->getArgument('shopOrderId'),
        ]);
        if (!$order) {
            throw new \RuntimeException('找不到退货单');
        }

        $this->insuranceFreightService->syncReturnOrder($order);

        return Command::SUCCESS;
    }
}
