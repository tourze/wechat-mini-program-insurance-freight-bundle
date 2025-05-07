<?php

namespace WechatMiniProgramInsuranceFreightBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum ErrorCode: int implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case MissingParameter = 2;
    case InsuranceTimeError = 1010;
    case DuplicateLogisticsNumber = 1011;
    case DuplicateActivation = 2001;
    case RechargeLimit = 2003;
    case ServiceNotActivated = 2004;
    case DuplicateClaim = 2007;
    case SystemSecurityReason = 2008;
    case InsurancePolicyNotFound = 2009;
    case OrderError_IdOrOpenid = 2011;
    case OrderError_NotPlacedInApp = 2012;
    case OrderError_PaymentTime = 2013;
    case OrderError_PaymentAmount = 2014;
    case OrderError_Other = 2015;
    case LogisticsNumberNoTrace = 2028;
    case InsufficientBalance = 4001;

    public function getLabel(): string
    {
        return match ($this) {
            self::MissingParameter => '缺少必要参数',
            self::InsuranceTimeError => '投保时间错误',
            self::DuplicateLogisticsNumber => '物流单号重复',
            self::DuplicateActivation => '重复开通',
            self::RechargeLimit => '充值金额限制，单次最高1万元',
            self::ServiceNotActivated => '未开通无忧退货',
            self::DuplicateClaim => '重复理赔',
            self::SystemSecurityReason => '系统安全原因，暂停理赔',
            self::InsurancePolicyNotFound => '未找到对应投保单',
            self::OrderError_IdOrOpenid => '订单错误 - 订单号/openid错误',
            self::OrderError_NotPlacedInApp => '订单错误 - 非该小程序内下单',
            self::OrderError_PaymentTime => '订单错误 - 支付时间错误',
            self::OrderError_PaymentAmount => '订单错误 - 支付金额错误',
            self::OrderError_Other => '订单错误 - 其他',
            self::LogisticsNumberNoTrace => '物流单号查不到轨迹',
            self::InsufficientBalance => '余额不足',
        };
    }
}
