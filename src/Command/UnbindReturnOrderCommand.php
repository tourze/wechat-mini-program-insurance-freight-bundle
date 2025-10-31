<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Exception\AccountNotFoundException;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnIdEmptyException;
use WechatMiniProgramInsuranceFreightBundle\Exception\ReturnOrderNotFoundException;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;
use WechatMiniProgramInsuranceFreightBundle\Request\UnbindReturnOrderRequest;
use Yiisoft\Json\Json;

#[AsCommand(name: self::NAME, description: '解绑单个退货信息')]
class UnbindReturnOrderCommand extends LockableCommand
{
    public const NAME = 'wechat-insurance:unbind-return-order';

    public function __construct(
        private readonly ReturnOrderRepository $orderRepository,
        private readonly Client $client,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('解绑单个退货信息')
            ->addArgument('shopOrderId', InputArgument::REQUIRED, '商家内部系统使用的退货编号')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $shopOrderId = $input->getArgument('shopOrderId');
        if (!is_string($shopOrderId)) {
            throw new \InvalidArgumentException('shopOrderId must be a string');
        }

        $order = $this->orderRepository->find($shopOrderId);
        if (null === $order) {
            throw new ReturnOrderNotFoundException($shopOrderId);
        }

        $request = new UnbindReturnOrderRequest();
        $account = $order->getAccount();
        if (null === $account) {
            throw new AccountNotFoundException();
        }
        $request->setAccount($account);

        $returnId = $order->getReturnId();
        if (null === $returnId) {
            throw new ReturnIdEmptyException();
        }
        $request->setReturnId($returnId);
        $response = $this->client->request($request);

        $output->writeln('解绑结果：' . Json::encode($response));

        return Command::SUCCESS;
    }
}
