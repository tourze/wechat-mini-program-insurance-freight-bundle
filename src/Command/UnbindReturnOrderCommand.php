<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use WechatMiniProgramBundle\Service\Client;
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
            ->addArgument('shopOrderId', InputArgument::REQUIRED, '商家内部系统使用的退货编号');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $order = $this->orderRepository->find($input->getArgument('shopOrderId'));
        if ($order === null) {
            throw new ReturnOrderNotFoundException($input->getArgument('shopOrderId'));
        }

        $request = new UnbindReturnOrderRequest();
        $request->setAccount($order->getAccount());
        $request->setReturnId($order->getReturnId());
        $response = $this->client->request($request);

        $output->writeln('解绑结果：' . Json::encode($response));

        return Command::SUCCESS;
    }
}
