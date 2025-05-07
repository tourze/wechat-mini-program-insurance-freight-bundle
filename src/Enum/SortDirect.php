<?php

namespace WechatMiniProgramInsuranceFreightBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 排序方式
 */
enum SortDirect: int implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case ASC = 0;
    case DESC = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::ASC => 'create_time正序',
            self::DESC => 'create_time倒序',
        };
    }
}
