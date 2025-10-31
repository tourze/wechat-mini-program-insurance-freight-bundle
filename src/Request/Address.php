<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Tourze\Arrayable\PlainArrayInterface;

/**
 * @implements PlainArrayInterface<string, mixed>
 */
class Address implements PlainArrayInterface
{
    private string $name;

    private string $mobile;

    private string $country;

    private string $province;

    private string $city;

    private string $area;

    private string $address;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

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

    public function getArea(): string
    {
        return $this->area;
    }

    public function setArea(string $area): void
    {
        $this->area = $area;
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
     * @param array<string, mixed> $item
     */
    public static function fromArray(array $item): self
    {
        $obj = new self();
        $obj->setName(self::extractStringValue($item, 'name'));
        $obj->setMobile(self::extractStringValue($item, 'mobile'));
        $obj->setCountry(self::extractStringValue($item, 'country'));
        $obj->setProvince(self::extractStringValue($item, 'province'));
        $obj->setCity(self::extractStringValue($item, 'city'));
        $obj->setArea(self::extractStringValue($item, 'area'));
        $obj->setAddress(self::extractStringValue($item, 'address'));

        return $obj;
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function extractStringValue(array $data, string $key): string
    {
        $value = $data[$key] ?? '';

        return is_string($value) ? $value : '';
    }

    /**
     * @return array<string, mixed>
     */
    public function retrievePlainArray(): array
    {
        return [
            'name' => $this->getName(),
            'mobile' => $this->getMobile(),
            'country' => $this->getCountry(),
            'province' => $this->getProvince(),
            'city' => $this->getCity(),
            'area' => $this->getArea(),
            'address' => $this->getAddress(),
        ];
    }
}
