<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 理赔接口
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html
 */
class ClaimRequest extends WithAccountRequest
{
    /**
     * 买家openid
     * 必须和理赔openid一致
     */
    private string $openid;

    /**
     * 微信支付单号
     * 一个微信支付单号只能投保一次
     */
    private string $orderNo;

    /**
     * 退款运单号
     * 理赔退款运单号唯一
     */
    private string $refundDeliveryNo;

    /**
     * 退款快递公司
     */
    private string $refundCompany;

    public function getRequestPath(): string
    {
        return '/wxa/business/insurance_freight/claim';
    }

    public function getRequestOptions(): ?array
    {
        $payload = [
            'openid' => $this->getOpenid(),
            'order_no' => $this->getOrderNo(),
            'refund_delivery_no' => $this->getRefundDeliveryNo(),
            'refund_company' => $this->getRefundCompany(),
        ];

        return [
            'json' => $payload,
        ];
    }

    public function getOpenid(): string
    {
        return $this->openid;
    }

    public function setOpenid(string $openid): void
    {
        $this->openid = $openid;
    }

    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getRefundDeliveryNo(): string
    {
        return $this->refundDeliveryNo;
    }

    public function setRefundDeliveryNo(string $refundDeliveryNo): void
    {
        $this->refundDeliveryNo = $refundDeliveryNo;
    }

    public function getRefundCompany(): string
    {
        return $this->refundCompany;
    }

    public function setRefundCompany(string $refundCompany): void
    {
        $this->refundCompany = $refundCompany;
    }
}
