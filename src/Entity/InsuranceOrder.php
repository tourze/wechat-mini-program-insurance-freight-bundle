<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;

/**
 * @implements ApiArrayInterface<string, mixed>
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: InsuranceOrderRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_insurance_freight_order', options: ['comment' => '运费险订单'])]
class InsuranceOrder implements ApiArrayInterface, AdminArrayInterface, \Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;

    #[ORM\ManyToOne]
    private ?Account $account = null;

    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '买家openid'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    private string $openId;

    #[ORM\Column(type: Types::STRING, length: 80, unique: true, options: ['comment' => '微信支付单号'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    private string $orderNo;

    #[ORM\Column(type: Types::INTEGER, enumType: InsuranceOrderStatus::class, options: ['comment' => '订单状态'])]
    #[Assert\NotNull]
    #[Assert\Choice(callback: [InsuranceOrderStatus::class, 'cases'])]
    private InsuranceOrderStatus $status;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '支付时间'])]
    #[Assert\NotNull]
    private \DateTimeInterface $payTime;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '支付金额（分）'])]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private int $payAmount;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '预估金额（分）'])]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private int $estimateAmount;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '保费（分）'])]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private int $premium;

    #[ORM\Column(type: Types::STRING, length: 100, options: ['comment' => '快递单号'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $deliveryNo;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '发货省份'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $deliveryPlaceProvince;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '发货城市'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $deliveryPlaceCity;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '发货区县'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $deliveryPlaceCounty;

    #[ORM\Column(type: Types::STRING, length: 200, options: ['comment' => '发货详细地址'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 200)]
    private string $deliveryPlaceAddress;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '收货省份'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $receiptPlaceProvince;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '收货城市'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $receiptPlaceCity;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '收货区县'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private string $receiptPlaceCounty;

    #[ORM\Column(type: Types::STRING, length: 200, options: ['comment' => '收货详细地址'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 200)]
    private string $receiptPlaceAddress;

    #[ORM\Column(type: Types::STRING, length: 100, options: ['comment' => '保单号'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $policyNo;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '保险结束时间'])]
    #[Assert\NotNull]
    private \DateTimeInterface $insuranceEndDate;

    #[ORM\Column(type: Types::STRING, length: 80, nullable: true, options: ['comment' => '退款运单号'])]
    #[Assert\Length(max: 80)]
    private ?string $refundDeliveryNo = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true, options: ['comment' => '退款公司'])]
    #[Assert\Length(max: 100)]
    private ?string $refundCompany = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '支付失败原因'])]
    #[Assert\Length(max: 65535)]
    private ?string $payFailReason = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '支付完成时间'])]
    #[Assert\Type(type: '\DateTimeInterface')]
    private ?\DateTimeInterface $payFinishTime = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否上门取件'])]
    #[Assert\Type(type: 'bool')]
    private ?bool $homePickUp = null;

    #[ORM\Column(length: 200, nullable: true, options: ['comment' => '投保订单在商家小程序的path'])]
    #[Assert\Length(max: 200)]
    private ?string $orderPath = null;

    /**
     * @var array<int, array<string, mixed>>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '投保订单商品列表'])]
    #[Assert\Type(type: 'array')]
    private ?array $goodsList = null;

    #[ORM\Column(type: Types::STRING, length: 80, nullable: true, options: ['comment' => '理赔报案号'])]
    #[Assert\Length(max: 80)]
    private ?string $reportNo = null;

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getPayTime(): \DateTimeInterface
    {
        return $this->payTime;
    }

    public function setPayTime(\DateTimeInterface $payTime): void
    {
        $this->payTime = $payTime;
    }

    public function getPayAmount(): int
    {
        return $this->payAmount;
    }

    public function setPayAmount(int $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    public function getEstimateAmount(): int
    {
        return $this->estimateAmount;
    }

    public function setEstimateAmount(int $estimateAmount): void
    {
        $this->estimateAmount = $estimateAmount;
    }

    public function getPremium(): int
    {
        return $this->premium;
    }

    public function setPremium(int $premium): void
    {
        $this->premium = $premium;
    }

    public function getDeliveryNo(): string
    {
        return $this->deliveryNo;
    }

    public function setDeliveryNo(string $deliveryNo): void
    {
        $this->deliveryNo = $deliveryNo;
    }

    public function getDeliveryPlaceProvince(): string
    {
        return $this->deliveryPlaceProvince;
    }

    public function setDeliveryPlaceProvince(string $deliveryPlaceProvince): void
    {
        $this->deliveryPlaceProvince = $deliveryPlaceProvince;
    }

    public function getDeliveryPlaceCity(): string
    {
        return $this->deliveryPlaceCity;
    }

    public function setDeliveryPlaceCity(string $deliveryPlaceCity): void
    {
        $this->deliveryPlaceCity = $deliveryPlaceCity;
    }

    public function getDeliveryPlaceCounty(): string
    {
        return $this->deliveryPlaceCounty;
    }

    public function setDeliveryPlaceCounty(string $deliveryPlaceCounty): void
    {
        $this->deliveryPlaceCounty = $deliveryPlaceCounty;
    }

    public function getDeliveryPlaceAddress(): string
    {
        return $this->deliveryPlaceAddress;
    }

    public function setDeliveryPlaceAddress(string $deliveryPlaceAddress): void
    {
        $this->deliveryPlaceAddress = $deliveryPlaceAddress;
    }

    public function getReceiptPlaceProvince(): string
    {
        return $this->receiptPlaceProvince;
    }

    public function setReceiptPlaceProvince(string $receiptPlaceProvince): void
    {
        $this->receiptPlaceProvince = $receiptPlaceProvince;
    }

    public function getReceiptPlaceCity(): string
    {
        return $this->receiptPlaceCity;
    }

    public function setReceiptPlaceCity(string $receiptPlaceCity): void
    {
        $this->receiptPlaceCity = $receiptPlaceCity;
    }

    public function getReceiptPlaceCounty(): string
    {
        return $this->receiptPlaceCounty;
    }

    public function setReceiptPlaceCounty(string $receiptPlaceCounty): void
    {
        $this->receiptPlaceCounty = $receiptPlaceCounty;
    }

    public function getReceiptPlaceAddress(): string
    {
        return $this->receiptPlaceAddress;
    }

    public function setReceiptPlaceAddress(string $receiptPlaceAddress): void
    {
        $this->receiptPlaceAddress = $receiptPlaceAddress;
    }

    public function getPolicyNo(): string
    {
        return $this->policyNo;
    }

    public function setPolicyNo(string $policyNo): void
    {
        $this->policyNo = $policyNo;
    }

    public function getInsuranceEndDate(): \DateTimeInterface
    {
        return $this->insuranceEndDate;
    }

    public function setInsuranceEndDate(\DateTimeInterface $insuranceEndDate): void
    {
        $this->insuranceEndDate = $insuranceEndDate;
    }

    public function getRefundDeliveryNo(): ?string
    {
        return $this->refundDeliveryNo;
    }

    public function setRefundDeliveryNo(?string $refundDeliveryNo): void
    {
        $this->refundDeliveryNo = $refundDeliveryNo;
    }

    public function getRefundCompany(): ?string
    {
        return $this->refundCompany;
    }

    public function setRefundCompany(?string $refundCompany): void
    {
        $this->refundCompany = $refundCompany;
    }

    public function getStatus(): InsuranceOrderStatus
    {
        return $this->status;
    }

    public function setStatus(InsuranceOrderStatus $status): void
    {
        $this->status = $status;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getPayFailReason(): ?string
    {
        return $this->payFailReason;
    }

    public function setPayFailReason(?string $payFailReason): void
    {
        $this->payFailReason = $payFailReason;
    }

    public function getPayFinishTime(): ?\DateTimeInterface
    {
        return $this->payFinishTime;
    }

    public function setPayFinishTime(?\DateTimeInterface $payFinishTime): void
    {
        $this->payFinishTime = $payFinishTime;
    }

    public function isHomePickUp(): ?bool
    {
        return $this->homePickUp;
    }

    public function setHomePickUp(?bool $homePickUp): void
    {
        $this->homePickUp = $homePickUp;
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
            'openId' => $this->getOpenId(),
            'orderNo' => $this->getOrderNo(),
            'status' => $this->getStatus()->toArray(),
            'estimateAmount' => $this->getEstimateAmount(),
            'policyNo' => $this->getPolicyNo(),
            'payFailReason' => $this->getPayFailReason(),
            'reportNo' => $this->getReportNo(),
            'goodsList' => $this->getGoodsList(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            ...$this->retrieveApiArray(),
            'payTime' => $this->getPayTime()->format('Y-m-d H:i:s'),
            'payAmount' => $this->getPayAmount(),
            'premium' => $this->getPremium(),
            'deliveryNo' => $this->getDeliveryNo(),

            'deliveryPlaceProvince' => $this->getDeliveryPlaceProvince(),
            'deliveryPlaceCity' => $this->getDeliveryPlaceCity(),
            'deliveryPlaceCounty' => $this->getDeliveryPlaceCounty(),
            'deliveryPlaceAddress' => $this->getDeliveryPlaceAddress(),

            'receiptPlaceProvince' => $this->getReceiptPlaceProvince(),
            'receiptPlaceCity' => $this->getReceiptPlaceCity(),
            'receiptPlaceCounty' => $this->getReceiptPlaceCounty(),
            'receiptPlaceAddress' => $this->getReceiptPlaceAddress(),

            'insuranceEndDate' => $this->getInsuranceEndDate()->format('Y-m-d H:i:s'),

            'refundDeliveryNo' => $this->getRefundDeliveryNo(),
            'refundCompany' => $this->getRefundCompany(),

            'payFinishTime' => $this->getPayFinishTime()?->format('Y-m-d H:i:s'),

            'homePickUp' => $this->isHomePickUp(),
            'orderPath' => $this->getOrderPath(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
