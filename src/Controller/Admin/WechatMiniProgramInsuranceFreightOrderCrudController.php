<?php

namespace WechatMiniProgramInsuranceFreightBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramInsuranceFreightBundle\Entity\InsuranceOrder;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;

/**
 * @extends AbstractCrudController<InsuranceOrder>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-insurance-freight/order',
    routeName: 'wechat_mini_program_insurance_freight_order',
)]
final class WechatMiniProgramInsuranceFreightOrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InsuranceOrder::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('运费险订单')
            ->setEntityLabelInPlural('运费险订单')
            ->setPageTitle('index', '运费险订单列表')
            ->setPageTitle('detail', '运费险订单详情')
            ->setPageTitle('edit', '编辑运费险订单')
            ->setPageTitle('new', '新建运费险订单')
            ->setDefaultSort(['createTime' => 'DESC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('ID'),
            TextField::new('openId')->setLabel('买家OpenID'),
            TextField::new('orderNo')->setLabel('微信支付单号'),
            ChoiceField::new('status')
                ->setLabel('订单状态')
                ->setChoices(InsuranceOrderStatus::cases())
                ->setFormTypeOption('choice_label', fn (InsuranceOrderStatus $status) => $status->getLabel())
                ->renderExpanded(false),
            DateTimeField::new('payTime')->setLabel('支付时间'),
            IntegerField::new('payAmount')->setLabel('支付金额（分）'),
            IntegerField::new('estimateAmount')->setLabel('预估金额（分）'),
            IntegerField::new('premium')->setLabel('保费（分）'),
            TextField::new('deliveryNo')->setLabel('快递单号'),
            TextField::new('policyNo')->setLabel('保单号'),
            DateTimeField::new('insuranceEndDate')->setLabel('保险结束时间'),
            TextField::new('refundDeliveryNo')->setLabel('退款运单号')->hideOnIndex(),
            TextField::new('refundCompany')->setLabel('退款公司')->hideOnIndex(),
            BooleanField::new('homePickUp')->setLabel('是否上门取件')->hideOnIndex(),
            TextField::new('orderPath')->setLabel('订单Path')->hideOnIndex(),
            // 使用 TextareaField 以确保在新建/编辑表单中可编辑并正确渲染
            TextareaField::new('goodsList')->setLabel('商品列表')->hideOnIndex(),
            TextField::new('reportNo')->setLabel('理赔报案号')->hideOnIndex(),
            DateTimeField::new('createTime')->setLabel('创建时间')->hideOnForm(),
            DateTimeField::new('updateTime')->setLabel('更新时间')->hideOnForm(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('openId', '买家OpenID'))
            ->add(TextFilter::new('orderNo', '微信支付单号'))
            ->add(ChoiceFilter::new('status', '订单状态')
                ->setChoices(array_combine(
                    array_map(static fn (InsuranceOrderStatus $status) => $status->getLabel(), InsuranceOrderStatus::cases()),
                    InsuranceOrderStatus::cases()
                )))
            ->add(DateTimeFilter::new('payTime', '支付时间'))
            ->add(TextFilter::new('deliveryNo', '快递单号'))
            ->add(TextFilter::new('policyNo', '保单号'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
