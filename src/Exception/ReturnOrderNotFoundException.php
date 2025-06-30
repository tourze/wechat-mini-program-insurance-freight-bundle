<?php

namespace WechatMiniProgramInsuranceFreightBundle\Exception;

class ReturnOrderNotFoundException extends \RuntimeException
{
    public function __construct(string $shopOrderId)
    {
        parent::__construct(sprintf('找不到退货单: %s', $shopOrderId));
    }
}