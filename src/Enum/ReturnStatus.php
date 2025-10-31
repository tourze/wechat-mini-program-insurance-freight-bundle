<?php

namespace WechatMiniProgramInsuranceFreightBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 退货单状态
 */
enum ReturnStatus: int implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case Waiting = 0;
    case Appointment = 1;
    case Filled = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::Waiting => '用户未填写退货信',
            self::Appointment => '预约上门取件',
            self::Filled => '填写自行寄回运单号',
        };
    }
}
