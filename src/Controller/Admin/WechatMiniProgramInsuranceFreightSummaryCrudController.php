<?php

namespace WechatMiniProgramInsuranceFreightBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;

class WechatMiniProgramInsuranceFreightSummaryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Summary::class;
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
