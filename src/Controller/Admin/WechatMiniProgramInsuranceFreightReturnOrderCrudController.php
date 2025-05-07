<?php

namespace WechatMiniProgramInsuranceFreightBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use WechatMiniProgramInsuranceFreightBundle\Entity\ReturnOrder;

class WechatMiniProgramInsuranceFreightReturnOrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReturnOrder::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
