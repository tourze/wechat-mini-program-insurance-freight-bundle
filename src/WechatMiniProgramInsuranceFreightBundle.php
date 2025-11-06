<?php

namespace WechatMiniProgramInsuranceFreightBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle;
use Tourze\EasyAdminMenuBundle\EasyAdminMenuBundle;
use WechatMiniProgramAuthBundle\WechatMiniProgramAuthBundle;
use WechatMiniProgramBundle\WechatMiniProgramBundle;

class WechatMiniProgramInsuranceFreightBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            DoctrineIndexedBundle::class => ['all' => true],
            WechatMiniProgramBundle::class => ['all' => true],
            WechatMiniProgramAuthBundle::class => ['all' => true],
            EasyAdminMenuBundle::class => ['all' => true],
        ];
    }
}
