<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Enum\ReturnStatus;
use WechatMiniProgramInsuranceFreightBundle\Repository\ReturnOrderRepository;

/**
 * @implements ApiArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: ReturnOrderRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_insurance_return_order', options: ['comment' => '退货单'])]
class ReturnOrder implements ApiArrayInterface, \Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;

    #[ORM\ManyToOne]
    private ?Account $account = null;

    #[ORM\Column(length: 64, unique: true, options: ['comment' => '商家内部系统使用的退货编号'])]
    #[Assert\Length(max: 64)]
    private ?string $shopOrderId = null;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: Types::JSON, options: ['comment' => '商家退货地址'])]
    #[Assert\NotNull]
    private array $bizAddress = [];

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: Types::JSON, options: ['comment' => '用户购物时的收货地址'])]
    #[Assert\NotNull]
    private array $userAddress = [];

    #[ORM\Column(length: 64, options: ['comment' => '退货用户的openid'])]
    #[Assert\Length(max: 64)]
    private ?string $openId = null;

    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '退货订单在商家小程序的path'])]
    #[Assert\Length(max: 255)]
    private ?string $orderPath = null;

    /**
     * @var array<int, array<string, mixed>>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '退货商品list'])]
    #[Assert\Type(type: 'array')]
    private ?array $goodsList = null;

    #[ORM\Column(length: 64, options: ['comment' => '微信支付单号'])]
    #[Assert\Length(max: 64)]
    private ?string $wxPayId = null;

    #[ORM\Column(length: 60, nullable: true, options: ['comment' => '运单号'])]
    #[Assert\Length(max: 60)]
    private ?string $waybillId = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, enumType: ReturnStatus::class, options: ['comment' => '退货状态'])]
    #[Assert\Choice(callback: [ReturnStatus::class, 'cases'])]
    private ?ReturnStatus $status = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, enumType: ReturnOrderStatus::class, options: ['comment' => '运单状态'])]
    #[Assert\Choice(callback: [ReturnOrderStatus::class, 'cases'])]
    private ?ReturnOrderStatus $orderStatus = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '运力公司名称'])]
    #[Assert\Length(max: 50)]
    private ?string $deliveryName = null;

    #[ORM\Column(length: 20, nullable: true, options: ['comment' => '运力公司编码'])]
    #[Assert\Length(max: 20)]
    private ?string $deliveryId = null;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '退货ID'])]
    #[Assert\Length(max: 50)]
    private ?string $returnId = null;

    #[ORM\Column(length: 80, nullable: true, options: ['comment' => '理赔报案号'])]
    #[IndexColumn]
    #[Assert\Length(max: 80)]
    private ?string $reportNo = null;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getShopOrderId(): ?string
    {
        return $this->shopOrderId;
    }

    public function setShopOrderId(string $shopOrderId): void
    {
        $this->shopOrderId = $shopOrderId;
    }

    /**
     * @return array<string, mixed>
     */
    public function getBizAddress(): array
    {
        return $this->bizAddress;
    }

    /**
     * @param array<string, mixed> $bizAddress
     */
    public function setBizAddress(array $bizAddress): void
    {
        $this->bizAddress = $bizAddress;
    }

    /**
     * 获取商家退货地址的字符串表示（用于表单显示）
     */
    public function getBizAddressString(): string
    {
        $result = json_encode($this->bizAddress, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if (false === $result) {
            return '';
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function getUserAddress(): array
    {
        return $this->userAddress;
    }

    /**
     * @param array<string, mixed> $userAddress
     */
    public function setUserAddress(array $userAddress): void
    {
        $this->userAddress = $userAddress;
    }

    /**
     * 获取用户收货地址的字符串表示（用于表单显示）
     */
    public function getUserAddressString(): string
    {
        $result = json_encode($this->userAddress, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if (false === $result) {
            return '';
        }

        return $result;
    }

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function getOrderPath(): ?string
    {
        return $this->orderPath;
    }

    public function setOrderPath(?string $orderPath): void
    {
        $this->orderPath = $orderPath;
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    public function getGoodsList(): ?array
    {
        return $this->goodsList;
    }

    /**
     * @param array<int, array<string, mixed>>|null $goodsList
     */
    public function setGoodsList(?array $goodsList): void
    {
        $this->goodsList = $goodsList;
    }

    /**
     * 获取商品列表的字符串表示（用于表单显示）
     */
    public function getGoodsListString(): string
    {
        $result = json_encode($this->goodsList, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if (false === $result) {
            return '';
        }

        return $result;
    }

    public function getWxPayId(): ?string
    {
        return $this->wxPayId;
    }

    public function setWxPayId(string $wxPayId): void
    {
        $this->wxPayId = $wxPayId;
    }

    public function getWaybillId(): ?string
    {
        return $this->waybillId;
    }

    public function setWaybillId(?string $waybillId): void
    {
        $this->waybillId = $waybillId;
    }

    public function getOrderStatus(): ?ReturnOrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?ReturnOrderStatus $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    public function getDeliveryName(): ?string
    {
        return $this->deliveryName;
    }

    public function setDeliveryName(?string $deliveryName): void
    {
        $this->deliveryName = $deliveryName;
    }

    public function getDeliveryId(): ?string
    {
        return $this->deliveryId;
    }

    public function setDeliveryId(?string $deliveryId): void
    {
        $this->deliveryId = $deliveryId;
    }

    public function getReturnId(): ?string
    {
        return $this->returnId;
    }

    public function setReturnId(?string $returnId): void
    {
        $this->returnId = $returnId;
    }

    public function getStatus(): ?ReturnStatus
    {
        return $this->status;
    }

    public function setStatus(?ReturnStatus $status): void
    {
        $this->status = $status;
    }

    public function getReportNo(): ?string
    {
        return $this->reportNo;
    }

    public function setReportNo(?string $reportNo): void
    {
        $this->reportNo = $reportNo;
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveApiArray(): array
    {
        return [
            'id' => $this->getId(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            'shopOrderId' => $this->getShopOrderId(),
            'bizAddress' => $this->getBizAddress(),
            'userAddress' => $this->getUserAddress(),
            'status' => $this->getStatus()?->toArray(),
            'orderStatus' => $this->getOrderStatus()?->toArray(),
            'returnId' => $this->getReturnId(),
            'deliveryName' => $this->getDeliveryName(),
            'deliveryId' => $this->getDeliveryId(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
