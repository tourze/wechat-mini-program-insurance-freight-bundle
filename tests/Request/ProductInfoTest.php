<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Request\Goods;
use WechatMiniProgramInsuranceFreightBundle\Request\ProductInfo;

/**
 * @internal
 */
#[CoversClass(ProductInfo::class)]
final class ProductInfoTest extends TestCase
{
    private ProductInfo $productInfo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productInfo = new ProductInfo();
    }

    public function testGettersAndSetters(): void
    {
        // 测试 OrderPath
        $orderPath = 'pages/order/detail?id=123';
        $this->productInfo->setOrderPath($orderPath);
        $this->assertEquals($orderPath, $this->productInfo->getOrderPath());

        // 测试 GoodsList
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证数组操作逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式可以提供更可控的测试环境。
        $goods1 = $this->createMock(Goods::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证数组操作逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式可以提供更可控的测试环境。
        $goods2 = $this->createMock(Goods::class);
        $goodsList = [$goods1, $goods2];

        $this->productInfo->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $this->productInfo->getGoodsList());
    }

    public function testAddGoods(): void
    {
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证addGoods方法的数组追加逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式可以提供更可控的测试环境。
        $goods1 = $this->createMock(Goods::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证第二个商品的处理逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式可以提供更可控的测试环境。
        $goods2 = $this->createMock(Goods::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其行为来验证addGoods方法追加第三个商品的逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式可以提供更可控的测试环境。
        $goods3 = $this->createMock(Goods::class);

        $this->productInfo->setGoodsList([$goods1, $goods2]);
        $this->productInfo->addGoods($goods3);

        $goodsList = $this->productInfo->getGoodsList();
        $this->assertCount(3, $goodsList);
        $this->assertSame($goods1, $goodsList[0]);
        $this->assertSame($goods2, $goodsList[1]);
        $this->assertSame($goods3, $goodsList[2]);
    }

    public function testRetrievePlainArray(): void
    {
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证数组转换逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式可以提供更可控的测试数据。
        $goods1 = $this->createMock(Goods::class);
        // Mock具体类说明: WechatMiniProgramInsuranceFreightBundle\Request\Goods是数据传输对象(DTO)，
        // 没有对应的接口定义，测试中需要模拟其retrievePlainArray方法来验证数组转换逻辑。
        // 使用具体类Mock是合理的，因为DTO类主要用于数据传输，本身不包含复杂业务逻辑。
        // 替代方案：可以创建真实的Goods对象，但Mock方式可以提供更可控的测试数据。
        $goods2 = $this->createMock(Goods::class);

        $goods1Array = ['name' => '商品1', 'url' => 'https://example.com/1.jpg'];
        $goods2Array = ['name' => '商品2', 'url' => 'https://example.com/2.jpg'];

        $goods1->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($goods1Array)
        ;

        $goods2->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($goods2Array)
        ;

        $this->productInfo->setOrderPath('pages/order/detail?id=123');
        $this->productInfo->setGoodsList([$goods1, $goods2]);

        $array = $this->productInfo->retrievePlainArray();

        $this->assertArrayHasKey('order_path', $array);
        $this->assertArrayHasKey('goods_list', $array);
        $this->assertEquals('pages/order/detail?id=123', $array['order_path']);
        $this->assertEquals([$goods1Array, $goods2Array], $array['goods_list']);
    }

    public function testRetrievePlainArrayWithEmptyGoodsList(): void
    {
        $this->productInfo->setOrderPath('pages/order/detail?id=456');
        $this->productInfo->setGoodsList([]);

        $array = $this->productInfo->retrievePlainArray();

        $this->assertArrayHasKey('order_path', $array);
        $this->assertArrayHasKey('goods_list', $array);
        $this->assertEquals('pages/order/detail?id=456', $array['order_path']);
        $this->assertEquals([], $array['goods_list']);
    }
}
