<?php

namespace WechatMiniProgramInsuranceFreightBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;

/**
 * @extends AbstractCrudController<ReturnOrder>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-insurance-freight/return-order',
    routeName: 'wechat_mini_program_insurance_freight_return_order',
)]
final class WechatMiniProgramInsuranceFreightReturnOrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReturnOrder::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('退货单')
            ->setEntityLabelInPlural('退货单')
            ->setPageTitle('index', '退货单列表')
            ->setPageTitle('detail', '退货单详情')
            ->setPageTitle('edit', '编辑退货单')
            ->setPageTitle('new', '新建退货单')
            ->setDefaultSort(['createTime' => 'DESC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('ID'),
            TextField::new('shopOrderId')->setLabel('商家退货编号'),
            TextField::new('openId')->setLabel('用户OpenID'),
            TextField::new('wxPayId')->setLabel('微信支付单号'),
            TextField::new('waybillId')->setLabel('运单号'),
            ChoiceField::new('status')
                ->setLabel('退货状态')
                ->setChoices(ReturnStatus::cases())
                ->setFormTypeOption('choice_label', fn (ReturnStatus $status) => $status->getLabel())
                ->renderExpanded(false),
            ChoiceField::new('orderStatus')
                ->setLabel('运单状态')
                ->setChoices(ReturnOrderStatus::cases())
                ->setFormTypeOption('choice_label', fn (ReturnOrderStatus $status) => $status->getLabel())
                ->renderExpanded(false),
            TextField::new('deliveryName')->setLabel('运力公司名称'),
            TextField::new('deliveryId')->setLabel('运力公司编码'),
            TextField::new('returnId')->setLabel('退货ID'),
            TextField::new('reportNo')->setLabel('理赔报案号'),
            TextField::new('orderPath')->setLabel('订单Path')->hideOnIndex(),
            TextareaField::new('bizAddressString')->setLabel('商家退货地址')->hideOnIndex(),
            TextareaField::new('userAddressString')->setLabel('用户收货地址')->hideOnIndex(),
            TextareaField::new('goodsListString')->setLabel('退货商品列表')->hideOnIndex(),
            DateTimeField::new('createTime')->setLabel('创建时间')->hideOnForm(),
            DateTimeField::new('updateTime')->setLabel('更新时间')->hideOnForm(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('shopOrderId', '商家退货编号'))
            ->add(TextFilter::new('openId', '用户OpenID'))
            ->add(TextFilter::new('wxPayId', '微信支付单号'))
            ->add(TextFilter::new('waybillId', '运单号'))
            ->add(ChoiceFilter::new('status', '退货状态')
                ->setChoices(array_combine(
                    array_map(static fn (ReturnStatus $status) => $status->getLabel(), ReturnStatus::cases()),
                    ReturnStatus::cases()
                )))
            ->add(ChoiceFilter::new('orderStatus', '运单状态')
                ->setChoices(array_combine(
                    array_map(static fn (ReturnOrderStatus $status) => $status->getLabel(), ReturnOrderStatus::cases()),
                    ReturnOrderStatus::cases()
                )))
            ->add(TextFilter::new('reportNo', '理赔报案号'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
