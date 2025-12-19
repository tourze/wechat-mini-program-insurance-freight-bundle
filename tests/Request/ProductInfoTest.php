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
        $orderPath = 'pages/order/detail?id=123';
        $this->productInfo->setOrderPath($orderPath);
        $this->assertEquals($orderPath, $this->productInfo->getOrderPath());

        $goods1 = new Goods();
        $goods2 = new Goods();
        $goodsList = [$goods1, $goods2];

        $this->productInfo->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $this->productInfo->getGoodsList());
    }

    public function testAddGoods(): void
    {
        $goods1 = new Goods();
        $goods2 = new Goods();
        $goods3 = new Goods();

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
        $goods1 = new Goods();
        $goods1->setName('商品1');
        $goods1->setUrl('https://example.com/1.jpg');

        $goods2 = new Goods();
        $goods2->setName('商品2');
        $goods2->setUrl('https://example.com/2.jpg');

        $this->productInfo->setOrderPath('pages/order/detail?id=123');
        $this->productInfo->setGoodsList([$goods1, $goods2]);

        $array = $this->productInfo->retrievePlainArray();

        $this->assertArrayHasKey('order_path', $array);
        $this->assertArrayHasKey('goods_list', $array);
        $this->assertEquals('pages/order/detail?id=123', $array['order_path']);
        $this->assertEquals([
            ['name' => '商品1', 'url' => 'https://example.com/1.jpg'],
            ['name' => '商品2', 'url' => 'https://example.com/2.jpg'],
        ], $array['goods_list']);
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
