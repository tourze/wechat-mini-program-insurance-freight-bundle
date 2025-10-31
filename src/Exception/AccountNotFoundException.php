<?php

namespace WechatMiniProgramInsuranceFreightBundle\Exception;

class AccountNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('退货订单缺少关联账户');
    }
}
