<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Request;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Request\Address;

/**
 * @internal
 */
#[CoversClass(Address::class)]
final class AddressTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testGettersAndSetters(): void
    {
        $address = new Address();

        $address->setName('张三');
        $this->assertEquals('张三', $address->getName());

        $address->setMobile('13800138000');
        $this->assertEquals('13800138000', $address->getMobile());

        $address->setCountry('中国');
        $this->assertEquals('中国', $address->getCountry());

        $address->setProvince('广东省');
        $this->assertEquals('广东省', $address->getProvince());

        $address->setCity('深圳市');
        $this->assertEquals('深圳市', $address->getCity());

        $address->setArea('南山区');
        $this->assertEquals('南山区', $address->getArea());

        $address->setAddress('科技园路1号');
        $this->assertEquals('科技园路1号', $address->getAddress());
    }

    public function testRetrievePlainArray(): void
    {
        $address = new Address();
        $address->setName('李四');
        $address->setMobile('13900139000');
        $address->setCountry('中国');
        $address->setProvince('北京市');
        $address->setCity('北京市');
        $address->setArea('朝阳区');
        $address->setAddress('建国路88号');

        $expected = [
            'name' => '李四',
            'mobile' => '13900139000',
            'country' => '中国',
            'province' => '北京市',
            'city' => '北京市',
            'area' => '朝阳区',
            'address' => '建国路88号',
        ];

        $this->assertEquals($expected, $address->retrievePlainArray());
    }

    public function testFromArray(): void
    {
        $data = [
            'name' => '王五',
            'mobile' => '13700137000',
            'country' => '中国',
            'province' => '上海市',
            'city' => '上海市',
            'area' => '浦东新区',
            'address' => '陆家嘴路168号',
        ];

        $address = Address::fromArray($data);

        $this->assertEquals('王五', $address->getName());
        $this->assertEquals('13700137000', $address->getMobile());
        $this->assertEquals('中国', $address->getCountry());
        $this->assertEquals('上海市', $address->getProvince());
        $this->assertEquals('上海市', $address->getCity());
        $this->assertEquals('浦东新区', $address->getArea());
        $this->assertEquals('陆家嘴路168号', $address->getAddress());
    }
}
