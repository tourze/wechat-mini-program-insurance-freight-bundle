<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Tourze\Arrayable\PlainArrayInterface;
use Yiisoft\Arrays\ArrayHelper;

/**
 * @implements PlainArrayInterface<string, mixed>
 */
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

    /**
     * @return array<string, mixed>
     */
    public function retrievePlainArray(): array
    {
        return [
            'province' => $this->getProvince(),
            'city' => $this->getCity(),
            'county' => $this->getCounty(),
            'address' => $this->getAddress(),
        ];
    }

    /**
     * @param array<string, mixed> $item
     */
    public static function fromArray(array $item): self
    {
        $obj = new self();
        $obj->setProvince(self::extractStringValue($item, 'province'));
        $obj->setCity(self::extractStringValue($item, 'city'));
        $obj->setCounty(self::extractStringValue($item, 'county'));
        $obj->setAddress(self::extractStringValue($item, 'address'));

        return $obj;
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function extractStringValue(array $data, string $key): string
    {
        $value = ArrayHelper::getValue($data, $key, '');

        return is_string($value) ? $value : '';
    }
}
