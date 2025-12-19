<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Carbon\CarbonInterface;
use WechatMiniProgramBundle\Request\WithAccountRequest;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\SortDirect;

/**
 * 拉取保单信息接口
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/freight_insurance.html#%E6%8B%89%E5%8F%96%E4%BF%9D%E5%8D%95%E4%BF%A1%E6%81%AF%E6%8E%A5%E5%8F%A3
 */
final class GetInsuranceOrderListRequest extends WithAccountRequest
{
    /**
     * @var string|null 买家openid
     */
    private ?string $openId = null;

    /**
     * @var string|null 微信支付单号
     */
    private ?string $orderNo = null;

    /**
     * @var string|null 保单号
     */
    private ?string $policyNo = null;

    /**
     * @var string|null 理赔报案号
     */
    private ?string $reportNo = null;

    /**
     * @var string|null 发货运单号
     */
    private ?string $deliveryNo = null;

    /**
     * @var string|null 退款运单号
     */
    private ?string $refundDeliveryNo = null;

    /**
     * @var CarbonInterface|null 查询开始时间
     */
    private ?CarbonInterface $beginTime = null;

    /**
     * @var CarbonInterface|null 查询结束时间
     */
    private ?CarbonInterface $endTime = null;

    /**
     * @var array<int, InsuranceOrderStatus>|null
     */
    private ?array $statusList = null;

    /**
     * @var int|null 分页offset
     */
    private ?int $offset = null;

    /**
     * @var int|null 分页limit
     */
    private ?int $limit = null;

    /**
     * @var SortDirect|null 排序方式
     */
    private ?SortDirect $sortDirect = null;

    public function getRequestPath(): string
    {
        return '/wxa/business/insurance_freight/getorderlist';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $json = [];

        $json = array_merge($json, $this->getStringFields());
        $json = array_merge($json, $this->getTimeFields());
        $json = array_merge($json, $this->getComplexFields());
        $json = array_merge($json, $this->getPaginationFields());

        return ['json' => $json];
    }

    /**
     * 获取字符串字段
     *
     * @return array<string, mixed>
     */
    private function getStringFields(): array
    {
        $result = [];
        $fields = [
            'openid' => $this->getOpenId(),
            'order_no' => $this->getOrderNo(),
            'policy_no' => $this->getPolicyNo(),
            'report_no' => $this->getReportNo(),
            'delivery_no' => $this->getDeliveryNo(),
            'refund_delivery_no' => $this->getRefundDeliveryNo(),
        ];

        foreach ($fields as $key => $value) {
            if (null !== $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * 获取时间字段
     *
     * @return array<string, mixed>
     */
    private function getTimeFields(): array
    {
        $result = [];

        if (null !== $this->getBeginTime()) {
            $result['begin_time'] = $this->getBeginTime()->getTimestamp();
        }
        if (null !== $this->getEndTime()) {
            $result['end_time'] = $this->getEndTime()->getTimestamp();
        }

        return $result;
    }

    /**
     * 获取复杂字段
     *
     * @return array<string, mixed>
     */
    private function getComplexFields(): array
    {
        $result = [];

        if (null !== $this->getStatusList()) {
            $result['status_list'] = array_map(fn ($item) => $item->value, $this->getStatusList());
        }

        if (null !== $this->getSortDirect()) {
            $result['sort_direct'] = $this->getSortDirect()->value;
        }

        return $result;
    }

    /**
     * 获取分页字段
     *
     * @return array<string, mixed>
     */
    private function getPaginationFields(): array
    {
        $result = [];

        if (null !== $this->getOffset()) {
            $result['offset'] = $this->getOffset();
        }
        if (null !== $this->getLimit()) {
            $result['limit'] = $this->getLimit();
        }

        return $result;
    }

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(?string $openId): void
    {
        $this->openId = $openId;
    }

    public function getOrderNo(): ?string
    {
        return $this->orderNo;
    }

    public function setOrderNo(?string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getPolicyNo(): ?string
    {
        return $this->policyNo;
    }

    public function setPolicyNo(?string $policyNo): void
    {
        $this->policyNo = $policyNo;
    }

    public function getReportNo(): ?string
    {
        return $this->reportNo;
    }

    public function setReportNo(?string $reportNo): void
    {
        $this->reportNo = $reportNo;
    }

    public function getDeliveryNo(): ?string
    {
        return $this->deliveryNo;
    }

    public function setDeliveryNo(?string $deliveryNo): void
    {
        $this->deliveryNo = $deliveryNo;
    }

    public function getRefundDeliveryNo(): ?string
    {
        return $this->refundDeliveryNo;
    }

    public function setRefundDeliveryNo(?string $refundDeliveryNo): void
    {
        $this->refundDeliveryNo = $refundDeliveryNo;
    }

    public function getBeginTime(): ?CarbonInterface
    {
        return $this->beginTime;
    }

    public function setBeginTime(?CarbonInterface $beginTime): void
    {
        $this->beginTime = $beginTime;
    }

    public function getEndTime(): ?CarbonInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?CarbonInterface $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * @return array<int, InsuranceOrderStatus>|null
     */
    public function getStatusList(): ?array
    {
        return $this->statusList;
    }

    /**
     * @param array<int, InsuranceOrderStatus>|null $statusList
     */
    public function setStatusList(?array $statusList): void
    {
        $this->statusList = $statusList;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(?int $offset): void
    {
        $this->offset = $offset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    public function getSortDirect(): ?SortDirect
    {
        return $this->sortDirect;
    }

    public function setSortDirect(?SortDirect $sortDirect): void
    {
        $this->sortDirect = $sortDirect;
    }
}
