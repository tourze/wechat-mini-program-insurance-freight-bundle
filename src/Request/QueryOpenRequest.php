<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 查询开通状态接口
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html
 */
class QueryOpenRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return '/wxa/business/insurance_freight/query_open';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        return [
            'body' => '',
        ];
    }
}
