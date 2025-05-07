<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Tourze\Arrayable\PlainArrayInterface;

class ProductInfo implements PlainArrayInterface
{
    /**
     * @var string 投保订单在商家小程序的path
     */
    private string $orderPath;

    /**
     * @var Goods[] 投保订单商品列表
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

    public function getGoodsList(): array
    {
        return $this->goodsList;
    }

    public function setGoodsList(array $goodsList): void
    {
        $this->goodsList = $goodsList;
    }

    public function addGoods(Goods $goods): void
    {
        $this->goodsList[] = $goods;
    }

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
