<?php

namespace WechatMiniProgramInsuranceFreightBundle\Exception;

class ReturnIdEmptyException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('退货ID不能为空');
    }
}
