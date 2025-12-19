<?php

namespace WechatMiniProgramInsuranceFreightBundle\Exception;

final class ReturnIdEmptyException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('退货ID不能为空');
    }
}
