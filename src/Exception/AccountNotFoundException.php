<?php

namespace WechatMiniProgramInsuranceFreightBundle\Exception;

final class AccountNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('退货订单缺少关联账户');
    }
}
