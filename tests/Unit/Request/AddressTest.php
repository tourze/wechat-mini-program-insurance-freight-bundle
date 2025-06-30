<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Request\Address;

class AddressTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $address = new Address();
        
        $address->setProvince('广东省');
        $this->assertEquals('广东省', $address->getProvince());
        
        $address->setCity('深圳市');
        $this->assertEquals('深圳市', $address->getCity());
        
        $address->setArea('南山区');
        $this->assertEquals('南山区', $address->getArea());
        
        $address->setAddress('科技园路1号');
        $this->assertEquals('科技园路1号', $address->getAddress());
    }
}