<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 开通无忧退货接口
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html#%E5%BC%80%E9%80%9A%E6%97%A0%E5%BF%A7%E9%80%80%E8%B4%A7%E6%8E%A5%E5%8F%A3
 */
final class SubmitOpenRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return '/wxa/business/insurance_freight/open';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        return [];
    }
}
