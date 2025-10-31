<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Request\Goods;

/**
 * @internal
 */
#[CoversClass(Goods::class)]
final class GoodsTest extends TestCase
{
    private Goods $goods;

    protected function setUp(): void
    {
        parent::setUp();

        $this->goods = new Goods();
    }

    public function testGettersAndSetters(): void
    {
        // 测试 Name
        $name = '测试商品';
        $this->goods->setName($name);
        $this->assertEquals($name, $this->goods->getName());

        // 测试 Url
        $url = 'https://example.com/goods/image.jpg';
        $this->goods->setUrl($url);
        $this->assertEquals($url, $this->goods->getUrl());
    }

    public function testFromArray(): void
    {
        $data = [
            'name' => '商品名称',
            'url' => 'https://example.com/product.jpg',
        ];

        $goods = Goods::fromArray($data);

        $this->assertEquals($data['name'], $goods->getName());
        $this->assertEquals($data['url'], $goods->getUrl());
    }

    public function testRetrievePlainArray(): void
    {
        $this->goods->setName('测试商品');
        $this->goods->setUrl('https://example.com/test.jpg');

        $array = $this->goods->retrievePlainArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals('测试商品', $array['name']);
        $this->assertEquals('https://example.com/test.jpg', $array['url']);
    }
}
