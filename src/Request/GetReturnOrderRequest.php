<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 查询退货 ID 状态
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html#%E6%9F%A5%E8%AF%A2%E9%80%80%E8%B4%A7-ID-%E7%8A%B6%E6%80%81
 */
class GetReturnOrderRequest extends WithAccountRequest
{
    /**
     * @var string 退货ID
     */
    private string $returnId;

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/delivery/no_worry_return/get';
    }

    public function getRequestOptions(): ?array
    {
        $payload = [
            'return_id' => $this->getReturnId(),
        ];

        return [
            'json' => $payload,
        ];
    }

    public function getReturnId(): string
    {
        return $this->returnId;
    }

    public function setReturnId(string $returnId): void
    {
        $this->returnId = $returnId;
    }
}
