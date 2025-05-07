<?php

namespace WechatMiniProgramInsuranceFreightBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 保单状态
 */
enum InsuranceOrderStatus: int implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case Securing = 2;
    case Claiming = 4;
    case ClaimSuccessful = 5;
    case ClaimFailed = 6;
    case InsuranceExpired = 7;

    public function getLabel(): string
    {
        return match ($this) {
            self::Securing => '保障中',
            self::Claiming => '理赔中',
            self::ClaimSuccessful => '理赔成功',
            self::ClaimFailed => '理赔失败',
            self::InsuranceExpired => '投保过期',
        };
    }
}
