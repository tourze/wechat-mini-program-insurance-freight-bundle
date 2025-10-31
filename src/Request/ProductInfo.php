<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Tourze\Arrayable\PlainArrayInterface;

/**
 * @implements PlainArrayInterface<string, mixed>
 */
class ProductInfo implements PlainArrayInterface
{
    /**
     * @var string 投保订单在商家小程序的path
     */
    private string $orderPath;

    /**
     * @var array<int, Goods> 投保订单商品列表
     */
    private array $goodsList;

    public function getOrderPath(): string
    {
        return $this->orderPath;
    }

    public function setOrderPath(string $orderPath): void
    {
        $this->orderPath = $orderPath;
    }

    /**
     * @return array<int, Goods>
     */
    public function getGoodsList(): array
    {
        return $this->goodsList;
    }

    /**
     * @param array<int, Goods> $goodsList
     */
    public function setGoodsList(array $goodsList): void
    {
        $this->goodsList = $goodsList;
    }

    public function addGoods(Goods $goods): void
    {
        $this->goodsList[] = $goods;
    }

    /**
     * @return array<string, mixed>
     */
    public function retrievePlainArray(): array
    {
        $goodsList = [];
        foreach ($this->getGoodsList() as $goods) {
            $goodsList[] = $goods->retrievePlainArray();
        }

        return [
            'order_path' => $this->getOrderPath(),
            'goods_list' => $goodsList,
        ];
    }
}
