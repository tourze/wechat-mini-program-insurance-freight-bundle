<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatMiniProgramInsuranceFreightBundle\Service\AdminMenu;

/**
 * 管理菜单服务测试
 *
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    protected function onSetUp(): void
    {
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testServiceImplementsMenuProviderInterface(): void
    {
        $this->assertInstanceOf(MenuProviderInterface::class, $this->adminMenu);
    }

    public function testServiceCanBeConstructed(): void
    {
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);
    }

    public function testServiceIsCallable(): void
    {
        $this->assertIsCallable($this->adminMenu);
    }

    /**
     * 测试 __invoke 方法能够正常执行
     */
    public function testInvokeMethod(): void
    {
        // 创建顶级菜单项 Mock
        $mockItem = $this->createMock(ItemInterface::class);

        // 创建微信管理子菜单 Mock
        $mockWechatMenu = $this->createMock(ItemInterface::class);

        // 创建运费险管理子菜单 Mock
        $mockInsuranceMenu = $this->createMock(ItemInterface::class);

        // 创建用于链式调用的子菜单项 Mock
        $mockSubMenuItem = $this->createMock(ItemInterface::class);
        $mockSubMenuItem->method('setUri')->willReturnSelf();
        $mockSubMenuItem->method('setAttribute')->willReturnSelf();

        // 验证顶级菜单的调用序列
        $mockItem->expects($this->exactly(2))
            ->method('getChild')
            ->with('微信管理')
            ->willReturnOnConsecutiveCalls(null, $mockWechatMenu)
        ;

        $mockItem->expects($this->once())
            ->method('addChild')
            ->with('微信管理')
            ->willReturn($mockWechatMenu)
        ;

        // 验证微信管理菜单的调用序列
        $mockWechatMenu->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fab fa-weixin')
            ->willReturnSelf()
        ;

        $mockWechatMenu->expects($this->exactly(2))
            ->method('getChild')
            ->with('运费险管理')
            ->willReturnOnConsecutiveCalls(null, $mockInsuranceMenu)
        ;

        $mockWechatMenu->expects($this->once())
            ->method('addChild')
            ->with('运费险管理')
            ->willReturn($mockInsuranceMenu)
        ;

        // 验证运费险管理菜单的调用序列
        $mockInsuranceMenu->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fas fa-shield-alt')
            ->willReturnSelf()
        ;

        $mockInsuranceMenu->expects($this->exactly(3))
            ->method('addChild')
            ->willReturnCallback(function (string $name) use ($mockSubMenuItem): ItemInterface {
                $this->assertContains($name, ['运费险订单', '退货单', '定时统计']);

                return $mockSubMenuItem;
            })
        ;

        // 调用 __invoke 方法
        ($this->adminMenu)($mockItem);
    }

    /**
     * 测试构造函数依赖注入
     */
    public function testConstructorDependencies(): void
    {
        $reflection = new \ReflectionClass($this->adminMenu);
        $this->assertTrue($reflection->hasProperty('linkGenerator'));

        // 验证属性为只读
        $linkGeneratorProperty = $reflection->getProperty('linkGenerator');
        $this->assertTrue($linkGeneratorProperty->isReadOnly());
    }
}
