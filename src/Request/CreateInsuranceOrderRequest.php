<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 投保接口(发货时投保)
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html#%E6%8A%95%E4%BF%9D%E6%8E%A5%E5%8F%A3-%E5%8F%91%E8%B4%A7%E6%97%B6%E6%8A%95%E4%BF%9D
 */
class CreateInsuranceOrderRequest extends WithAccountRequest
{
    /**
     * @var string 必须和理赔openid一致
     */
    private string $openId;

    /**
     * @var string 微信支付单号，一个微信支付单号只能投保一次
     */
    private string $orderNo;

    /**
     * @var int 微信支付时间，秒级时间戳，时间误差3天内
     */
    private int $payTime;

    /**
     * @var int 微信支付金额，单位：分
     */
    private int $payAmount;

    /**
     * @var string 发货运单号
     */
    private string $deliveryNo;

    /**
     * @var Place 发货地址
     */
    private Place $deliveryPlace;

    /**
     * @var Place 收货地址
     */
    private Place $receiptPlace;

    /**
     * @var ProductInfo 投保订单信息，用于微信下发投保和理赔通知给用户，用户点击可查看投保订单，点击订单可跳回商家小程序
     */
    private ProductInfo $productInfo;

    public function getRequestPath(): string
    {
        return '/wxa/business/insurance_freight/createorder';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'openid' => $this->getOpenId(),
                'order_no' => $this->getOrderNo(),
                'pay_amount' => $this->getPayAmount(),
                'pay_time' => $this->getPayTime(),
                'delivery_no' => $this->getDeliveryNo(),
                'delivery_place' => $this->getDeliveryPlace()->retrievePlainArray(),
                'receipt_place' => $this->getReceiptPlace()->retrievePlainArray(),
                'product_info' => $this->getProductInfo()->retrievePlainArray(),
            ],
        ];
    }

    public function getOpenId(): string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getPayTime(): int
    {
        return $this->payTime;
    }

    public function setPayTime(int $payTime): void
    {
        $this->payTime = $payTime;
    }

    public function getPayAmount(): int
    {
        return $this->payAmount;
    }

    public function setPayAmount(int $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    public function getDeliveryNo(): string
    {
        return $this->deliveryNo;
    }

    public function setDeliveryNo(string $deliveryNo): void
    {
        $this->deliveryNo = $deliveryNo;
    }

    public function getDeliveryPlace(): Place
    {
        return $this->deliveryPlace;
    }

    public function setDeliveryPlace(Place $deliveryPlace): void
    {
        $this->deliveryPlace = $deliveryPlace;
    }

    public function getReceiptPlace(): Place
    {
        return $this->receiptPlace;
    }

    public function setReceiptPlace(Place $receiptPlace): void
    {
        $this->receiptPlace = $receiptPlace;
    }

    public function getProductInfo(): ProductInfo
    {
        return $this->productInfo;
    }

    public function setProductInfo(ProductInfo $productInfo): void
    {
        $this->productInfo = $productInfo;
    }
}
