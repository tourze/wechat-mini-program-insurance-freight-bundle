<?php

namespace WechatMiniProgramInsuranceFreightBundle\Request;

use Tourze\Arrayable\PlainArrayInterface;

/**
 * 投保订单商品
 */
class Goods implements PlainArrayInterface
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

    public function retrievePlainArray(): array
    {
        return [
            'name' => $this->getName(),
            'url' => $this->getUrl(),
        ];
    }

    public static function fromArray(array $item): self
    {
        $obj = new self();
        $obj->setName($item['name']);
        $obj->setUrl($item['url']);

        return $obj;
    }
}
