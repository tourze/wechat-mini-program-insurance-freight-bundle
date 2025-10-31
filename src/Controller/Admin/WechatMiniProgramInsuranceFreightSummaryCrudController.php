<?php

namespace WechatMiniProgramInsuranceFreightBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;

/**
 * @extends AbstractCrudController<Summary>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-insurance-freight/summary',
    routeName: 'wechat_mini_program_insurance_freight_summary',
)]
final class WechatMiniProgramInsuranceFreightSummaryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Summary::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('定时统计')
            ->setEntityLabelInPlural('定时统计')
            ->setPageTitle('index', '定时统计列表')
            ->setPageTitle('detail', '定时统计详情')
            ->setPageTitle('edit', '编辑定时统计')
            ->setPageTitle('new', '新建定时统计')
            ->setDefaultSort(['date' => 'DESC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('ID'),
            DateTimeField::new('date')->setLabel('日期'),
            IntegerField::new('total')->setLabel('投保总数'),
            IntegerField::new('claimNum')->setLabel('理赔总数'),
            IntegerField::new('claimSuccessNum')->setLabel('理赔成功数'),
            IntegerField::new('premium')->setLabel('当前保费（分）'),
            IntegerField::new('funds')->setLabel('当前账号余额（分）'),
            BooleanField::new('needClose')->setLabel('是否不能投保'),
            DateTimeField::new('createTime')->setLabel('创建时间')->hideOnForm(),
            DateTimeField::new('updateTime')->setLabel('更新时间')->hideOnForm(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('date', '日期'))
            ->add(NumericFilter::new('total', '投保总数'))
            ->add(NumericFilter::new('claimNum', '理赔总数'))
            ->add(NumericFilter::new('claimSuccessNum', '理赔成功数'))
            ->add(BooleanFilter::new('needClose', '是否不能投保'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
