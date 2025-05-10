<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Integration;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle;
use Tourze\DoctrineSnowflakeBundle\DoctrineSnowflakeBundle;
use Tourze\DoctrineTimestampBundle\DoctrineTimestampBundle;
use WechatMiniProgramBundle\WechatMiniProgramBundle;
use WechatMiniProgramInsuranceFreightBundle\WechatMiniProgramInsuranceFreightBundle;

class IntegrationTestKernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new DoctrineBundle();
        yield new DoctrineIndexedBundle();
        yield new DoctrineSnowflakeBundle();
        yield new DoctrineTimestampBundle();
        yield new WechatMiniProgramBundle();
        yield new WechatMiniProgramInsuranceFreightBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        // 基本框架配置
        $container->extension('framework', [
            'secret' => 'TEST_SECRET',
            'test' => true,
            'http_method_override' => false,
            'handle_all_throwables' => true,
            'php_errors' => [
                'log' => true,
            ],
        ]);

        // Doctrine 配置 - 使用内存数据库
        $container->extension('doctrine', [
            'dbal' => [
                'driver' => 'pdo_sqlite',
                'url' => 'sqlite:///:memory:',
            ],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'controller_resolver' => [
                    'auto_mapping' => false,
                ],
                'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
                'auto_mapping' => true,
                'mappings' => [
                    'WechatMiniProgramInsuranceFreightBundle' => [
                        'is_bundle' => false,
                        'type' => 'attribute',
                        'dir' => '%kernel.project_dir%/packages/wechat-mini-program-insurance-freight-bundle/src/Entity',
                        'prefix' => 'WechatMiniProgramInsuranceFreightBundle\Entity',
                    ],
                    'TestEntities' => [
                        'is_bundle' => false,
                        'type' => 'attribute',
                        'dir' => '%kernel.project_dir%/packages/wechat-mini-program-insurance-freight-bundle/tests/Integration/Entity',
                        'prefix' => 'WechatMiniProgramInsuranceFreightBundle\Tests\Integration\Entity',
                    ],
                ],
            ],
        ]);
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/log';
    }
} 