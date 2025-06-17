<?php

namespace WechatMiniProgramInsuranceFreightBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Enum\InsuranceOrderStatus;
use WechatMiniProgramInsuranceFreightBundle\Repository\InsuranceOrderRepository;

#[AsPermission(title: '运费险订单')]
#[Listable]
#[ORM\Entity(repositoryClass: InsuranceOrderRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_insurance_freight_order', options: ['comment' => '运费险订单'])]
class InsuranceOrder implements ApiArrayInterface, AdminArrayInterface
{
    use TimestampableAware;

    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ListColumn(title: '小程序')]
    #[ORM\ManyToOne]
    private ?Account $account = null;

    #[ListColumn]
    #[IndexColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '买家openid'])]
    private string $openId;

    #[ListColumn]
    #[IndexColumn]
    #[ORM\Column(type: Types::STRING, length: 80, unique: true, options: ['comment' => '微信支付单号'])]
    private string $orderNo;

    #[ListColumn]
    #[ORM\Column(type: Types::INTEGER, length: 20, nullable: true, enumType: InsuranceOrderStatus::class, options: ['comment' => '状态'])]
    private InsuranceOrderStatus $status;

    #[ListColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false, options: ['comment' => '微信支付时间'])]
    private \DateTimeInterface $payTime;

    #[ListColumn(sorter: true)]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '微信支付金额 单位：分'])]
    private int $payAmount;

    #[ListColumn(sorter: true)]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '预估理赔金额 单位：分'])]
    private int $estimateAmount;

    #[ListColumn(sorter: true)]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '保费 单位：分'])]
    private int $premium;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '发货运单号'])]
    private string $deliveryNo;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '发货地址 省'])]
    private string $deliveryPlaceProvince;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '发货地址 市'])]
    private string $deliveryPlaceCity;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '发货地址 区'])]
    private string $deliveryPlaceCounty;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '发货地址 详细地址'])]
    private string $deliveryPlaceAddress;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '收货地址 省'])]
    private string $receiptPlaceProvince;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '收货地址 市'])]
    private string $receiptPlaceCity;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '收货地址 区'])]
    private string $receiptPlaceCounty;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '收货地址 详细地址'])]
    private string $receiptPlaceAddress;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, options: ['comment' => '保单号'])]
    private string $policyNo;

    #[ListColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false, options: ['comment' => '保险止期'])]
    private \DateTimeInterface $insuranceEndDate;

    #[ListColumn]
    #[IndexColumn]
    #[ORM\Column(type: Types::STRING, length: 80, nullable: true, options: ['comment' => '退款运单号'])]
    private ?string $refundDeliveryNo = null;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 80, nullable: true, options: ['comment' => '退款快递公司'])]
    private ?string $refundCompany = null;

    #[ListColumn]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '理赔打款失败原因'])]
    private ?string $payFailReason = null;

    #[ListColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '理赔款打给用户的时间'])]
    private ?\DateTimeInterface $payFinishTime = null;

    #[ListColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '是否上门取件'])]
    private ?bool $homePickUp = null;

    #[ORM\Column(length: 200, nullable: true, options: ['comment' => '投保订单在商家小程序的path'])]
    private ?string $orderPath = null;

    #[ORM\Column(nullable: true, options: ['comment' => '投保订单商品列表'])]
    private ?array $goodsList = null;

    #[ListColumn]
    #[IndexColumn]
    #[ORM\Column(type: Types::STRING, length: 80, nullable: true, options: ['comment' => '理赔报案号'])]
    private ?string $reportNo = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(?string $openId): void
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

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getPayFailReason(): ?string
    {
        return $this->payFailReason;
    }

    public function setPayFailReason(?string $payFailReason): static
    {
        $this->payFailReason = $payFailReason;

        return $this;
    }

    public function getPayFinishTime(): ?\DateTimeInterface
    {
        return $this->payFinishTime;
    }

    public function setPayFinishTime(?\DateTimeInterface $payFinishTime): static
    {
        $this->payFinishTime = $payFinishTime;

        return $this;
    }

    public function isHomePickUp(): ?bool
    {
        return $this->homePickUp;
    }

    public function setHomePickUp(?bool $homePickUp): static
    {
        $this->homePickUp = $homePickUp;

        return $this;
    }

    public function getOrderPath(): ?string
    {
        return $this->orderPath;
    }

    public function setOrderPath(?string $orderPath): static
    {
        $this->orderPath = $orderPath;

        return $this;
    }

    public function getGoodsList(): ?array
    {
        return $this->goodsList;
    }

    public function setGoodsList(?array $goodsList): static
    {
        $this->goodsList = $goodsList;

        return $this;
    }

    public function getReportNo(): ?string
    {
        return $this->reportNo;
    }

    public function setReportNo(?string $reportNo): void
    {
        $this->reportNo = $reportNo;
    }

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
}
