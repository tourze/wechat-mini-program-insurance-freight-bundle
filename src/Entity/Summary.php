<?php

namespace WechatMiniProgramInsuranceFreightBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Repository\SummaryRepository;

#[ORM\Entity(repositoryClass: SummaryRepository::class)]
#[ORM\Table(name: 'wechat_insurance_summary', options: ['comment' => '定时统计'])]
#[ORM\UniqueConstraint(name: 'wechat_insurance_summary_idx_uniq', columns: ['account_id', 'date'])]
class Summary implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }
    use TimestampableAware;

    #[ORM\ManyToOne]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '日期'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true, options: ['comment' => '投保总数'])]
    private ?int $total = null;

    #[ORM\Column(nullable: true, options: ['comment' => '理赔总数'])]
    private ?int $claimNum = null;

    #[ORM\Column(nullable: true, options: ['comment' => '理赔成功数'])]
    private ?int $claimSuccessNum = null;

    #[ORM\Column(nullable: true, options: ['comment' => '当前保费'])]
    private ?int $premium = null;

    #[ORM\Column(nullable: true, options: ['comment' => '当前账号余额'])]
    private ?int $funds = null;

    #[ORM\Column(nullable: true, options: ['comment' => '是否不能投保'])]
    private ?bool $needClose = null;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getClaimNum(): ?int
    {
        return $this->claimNum;
    }

    public function setClaimNum(?int $claimNum): static
    {
        $this->claimNum = $claimNum;

        return $this;
    }

    public function getClaimSuccessNum(): ?int
    {
        return $this->claimSuccessNum;
    }

    public function setClaimSuccessNum(?int $claimSuccessNum): static
    {
        $this->claimSuccessNum = $claimSuccessNum;

        return $this;
    }

    public function getPremium(): ?int
    {
        return $this->premium;
    }

    public function setPremium(?int $premium): static
    {
        $this->premium = $premium;

        return $this;
    }

    public function getFunds(): ?int
    {
        return $this->funds;
    }

    public function setFunds(?int $funds): static
    {
        $this->funds = $funds;

        return $this;
    }

    public function isNeedClose(): ?bool
    {
        return $this->needClose;
    }

    public function setNeedClose(?bool $needClose): static
    {
        $this->needClose = $needClose;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
