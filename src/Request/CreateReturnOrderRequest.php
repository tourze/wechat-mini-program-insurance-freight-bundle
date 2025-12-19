<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 创建退货 ID
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html#%E5%88%9B%E5%BB%BA%E9%80%80%E8%B4%A7-ID
 */
final class CreateReturnOrderRequest extends WithAccountRequest
{
    /**
     * @var string 商家内部系统使用的退货编号
     */
    private string $shopOrderId;

    /**
     * @var Address 商家退货地址
     */
    private Address $bizAddress;

    /**
     * @var Address 用户购物时的收货地址
     */
    private Address $userAddress;

    /**
     * @var string 必须和理赔openid一致
     */
    private string $openId;

    /**
     * @var string 填写已投保的微信支付单号
     */
    private string $wxPayId;

    /**
     * @var string 退货订单在商家小程序的path。如投保时已传入订单商品信息，则以投保时传入的为准
     */
    private string $orderPath;

    /**
     * @var array<int, Goods|array<string, mixed>> 退货商品list，一个元素为对象的数组,结构如下↓ 如投保时已传入订单商品信息，则以投保时传入的为准
     */
    private array $goodsList;

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/delivery/no_worry_return/add';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $goodsList = [];
        foreach ($this->getGoodsList() as $item) {
            $goodsList[] = $item instanceof Goods ? $item->retrievePlainArray() : (array) $item;
        }

        return [
            'json' => [
                'shop_order_id' => $this->getShopOrderId(),
                'biz_addr' => $this->getBizAddress()->retrievePlainArray(),
                'user_addr' => $this->getUserAddress()->retrievePlainArray(),
                'openid' => $this->getOpenId(),
                'wx_pay_id' => $this->getWxPayId(),
                'order_path' => $this->getOrderPath(),
                'goods_list' => $goodsList,
            ],
        ];
    }

    public function getShopOrderId(): string
    {
        return $this->shopOrderId;
    }

    public function setShopOrderId(string $shopOrderId): void
    {
        $this->shopOrderId = $shopOrderId;
    }

    public function getBizAddress(): Address
    {
        return $this->bizAddress;
    }

    public function setBizAddress(Address $bizAddress): void
    {
        $this->bizAddress = $bizAddress;
    }

    public function getUserAddress(): Address
    {
        return $this->userAddress;
    }

    public function setUserAddress(Address $userAddress): void
    {
        $this->userAddress = $userAddress;
    }

    public function getOpenId(): string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function getWxPayId(): string
    {
        return $this->wxPayId;
    }

    public function setWxPayId(string $wxPayId): void
    {
        $this->wxPayId = $wxPayId;
    }

    public function getOrderPath(): string
    {
        return $this->orderPath;
    }

    public function setOrderPath(string $orderPath): void
    {
        $this->orderPath = $orderPath;
    }

    /**
     * @return array<int, Goods|array<string, mixed>>
     */
    public function getGoodsList(): array
    {
        return $this->goodsList;
    }

    /**
     * @param array<int, Goods|array<string, mixed>> $goodsList
     */
    public function setGoodsList(array $goodsList): void
    {
        $this->goodsList = $goodsList;
    }

    public function addGoods(Goods $goods): void
    {
        $this->goodsList[] = $goods;
    }
}
