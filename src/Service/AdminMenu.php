<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        // 创建微信管理顶级菜单（如果不存在）
        if (null === $item->getChild('微信管理')) {
            $item->addChild('微信管理')
                ->setAttribute('icon', 'fab fa-weixin')
            ;
        }

        $wechatMenu = $item->getChild('微信管理');
        if (null === $wechatMenu) {
            return;
        }

        // 创建运费险管理子菜单
        if (null === $wechatMenu->getChild('运费险管理')) {
            $wechatMenu->addChild('运费险管理')
                ->setAttribute('icon', 'fas fa-shield-alt')
            ;
        }

        $insuranceMenu = $wechatMenu->getChild('运费险管理');
        if (null === $insuranceMenu) {
            return;
        }

        // 运费险订单管理
        $insuranceMenu
            ->addChild('运费险订单')
            ->setUri($this->linkGenerator->getCurdListPage(InsuranceOrder::class))
            ->setAttribute('icon', 'fas fa-file-invoice')
        ;

        // 退货单管理
        $insuranceMenu
            ->addChild('退货单')
            ->setUri($this->linkGenerator->getCurdListPage(ReturnOrder::class))
            ->setAttribute('icon', 'fas fa-undo-alt')
        ;

        // 定时统计
        $insuranceMenu
            ->addChild('定时统计')
            ->setUri($this->linkGenerator->getCurdListPage(Summary::class))
            ->setAttribute('icon', 'fas fa-chart-bar')
        ;
    }
}
