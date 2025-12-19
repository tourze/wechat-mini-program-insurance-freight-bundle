<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Tourze\Arrayable\PlainArrayInterface;

/**
 * 投保订单商品
 * @implements PlainArrayInterface<string, mixed>
 */
final class Goods implements PlainArrayInterface
{
    /**
     * @var string 投保商品名称
     */
    private string $name;

    /**
     * @var string 投保商品图片url
     */
    private string $url;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return array<string, mixed>
     */
    public function retrievePlainArray(): array
    {
        return [
            'name' => $this->getName(),
            'url' => $this->getUrl(),
        ];
    }

    /**
     * @param array<string, mixed> $item
     */
    public static function fromArray(array $item): self
    {
        $obj = new self();
        $obj->setName(self::extractStringValue($item, 'name'));
        $obj->setUrl(self::extractStringValue($item, 'url'));

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
}
