<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Carbon\CarbonInterface;
use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 拉取摘要接口
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html
 */
final class GetSummaryRequest extends WithAccountRequest
{
    /**
     * 查询开始时间
     */
    private CarbonInterface $beginTime;

    /**
     * 查询结束时间戳
     */
    private CarbonInterface $endTime;

    public function getRequestPath(): string
    {
        return '/wxa/business/insurance_freight/getsummary';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $payload = [
            'begin_time' => $this->getBeginTime()->getTimestamp(),
            'end_time' => $this->getBeginTime()->getTimestamp(),
        ];

        return [
            'json' => $payload,
        ];
    }

    public function getBeginTime(): CarbonInterface
    {
        return $this->beginTime;
    }

    public function setBeginTime(CarbonInterface $beginTime): void
    {
        $this->beginTime = $beginTime;
    }

    public function getEndTime(): CarbonInterface
    {
        return $this->endTime;
    }

    public function setEndTime(CarbonInterface $endTime): void
    {
        $this->endTime = $endTime;
    }
}
