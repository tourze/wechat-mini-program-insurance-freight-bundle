<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 解绑退货 ID
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html#%E8%A7%A3%E7%BB%91%E9%80%80%E8%B4%A7-ID
 */
final class UnbindReturnOrderRequest extends WithAccountRequest
{
    /**
     * @var string 退货ID
     */
    private string $returnId;

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/delivery/no_worry_return/unbind';
    }

    /**
     * @return array<string, mixed>|null
     */
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
