<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Request\Goods;
use WechatMiniProgramInsuranceFreightBundle\Request\ProductInfo;

class ProductInfoTest extends TestCase
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
        $goods1 = $this->createMock(Goods::class);
        $goods2 = $this->createMock(Goods::class);
        $goodsList = [$goods1, $goods2];
        
        $this->productInfo->setGoodsList($goodsList);
        $this->assertEquals($goodsList, $this->productInfo->getGoodsList());
    }
    
    public function testAddGoods(): void
    {
        $goods1 = $this->createMock(Goods::class);
        $goods2 = $this->createMock(Goods::class);
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
        $goods1 = $this->createMock(Goods::class);
        $goods2 = $this->createMock(Goods::class);
        
        $goods1Array = ['name' => '商品1', 'url' => 'https://example.com/1.jpg'];
        $goods2Array = ['name' => '商品2', 'url' => 'https://example.com/2.jpg'];
        
        $goods1->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($goods1Array);
        
        $goods2->expects($this->once())
            ->method('retrievePlainArray')
            ->willReturn($goods2Array);
        
        $this->productInfo->setOrderPath('pages/order/detail?id=123');
        $this->productInfo->setGoodsList([$goods1, $goods2]);
        
        $array = $this->productInfo->retrievePlainArray();
        
        $this->assertArrayHasKey('order_path', $array);
        $this->assertArrayHasKey('goods_list', $array);
        $this->assertEquals('pages/order/detail?id=123', $array['order_path']);
        $this->assertEquals([$goods1Array, $goods2Array], $array['goods_list']);
    }
    
    public function testRetrievePlainArray_withEmptyGoodsList(): void
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