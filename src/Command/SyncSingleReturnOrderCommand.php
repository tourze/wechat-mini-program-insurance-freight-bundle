<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnOrderNotFoundException;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Service\InsuranceFreightService;

#[AsCommand(name: self::NAME, description: '同步单个退货信息到本地')]
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
            ->addArgument('shopOrderId', InputArgument::REQUIRED, '商家内部系统使用的退货编号')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $shopOrderId = $input->getArgument('shopOrderId');
        if (!is_string($shopOrderId)) {
            throw new \InvalidArgumentException('shopOrderId must be a string');
        }

        $order = $this->orderRepository->findOneBy([
            'shopOrderId' => $shopOrderId,
        ]);
        if (!$order instanceof ReturnOrder) {
            throw new ReturnOrderNotFoundException($shopOrderId);
        }

        $this->insuranceFreightService->syncReturnOrder($order);

        return Command::SUCCESS;
    }
}
