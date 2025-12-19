<?php

namespace WechatMiniProgramInsuranceFreightBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramInsuranceFreightBundle\Request\QueryOpenRequest;
use Yiisoft\Json\Json;

#[AsCommand(name: self::NAME, description: '查询开通状态')]
final class QueryOpenCommand extends Command
{
    public const NAME = 'wechat-insurance:query-open';

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $request = new QueryOpenRequest();
            $request->setAccount($account);
            $response = $this->client->request($request);
            $output->writeln("{$account}: " . Json::encode($response));
        }

        return Command::SUCCESS;
    }
}
