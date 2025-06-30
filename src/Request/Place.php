<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Tourze\Arrayable\PlainArrayInterface;
use Yiisoft\Arrays\ArrayHelper;

class Place implements PlainArrayInterface
{
    /**
     * @var string 省
     */
    private string $province;

    /**
     * @var string 市
     */
    private string $city;

    /**
     * @var string 区
     */
    private string $county;

    /**
     * @var string 详细地址
     */
    private string $address;

    public function getProvince(): string
    {
        return $this->province;
    }

    public function setProvince(string $province): void
    {
        $this->province = $province;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCounty(): string
    {
        return $this->county;
    }

    public function setCounty(string $county): void
    {
        $this->county = $county;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function retrievePlainArray(): array
    {
        return [
            'province' => $this->getProvince(),
            'city' => $this->getCity(),
            'county' => $this->getCounty(),
            'address' => $this->getAddress(),
        ];
    }

    public static function fromArray(array $item): self
    {
        $obj = new self();
        $obj->setProvince(ArrayHelper::getValue($item, 'province', ''));
        $obj->setCity(ArrayHelper::getValue($item, 'city', ''));
        $obj->setCounty(ArrayHelper::getValue($item, 'county', ''));
        $obj->setAddress(ArrayHelper::getValue($item, 'address', ''));

        return $obj;
    }
}
