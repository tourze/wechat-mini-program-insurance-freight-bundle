<?php

declare(strict_types=1);

namespace WechatMiniProgramInsuranceFreightBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramInsuranceFreightBundle\Repository\SummaryRepository;

#[ORM\Entity(repositoryClass: SummaryRepository::class)]
#[ORM\Table(name: 'wechat_insurance_summary', options: ['comment' => '定时统计'])]
#[ORM\UniqueConstraint(name: 'wechat_insurance_summary_idx_uniq', columns: ['account_id', 'date'])]
class Summary implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    #[ORM\ManyToOne]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '日期'])]
    #[Assert\NotNull]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '投保总数'])]
    #[Assert\PositiveOrZero]
    private ?int $total = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '理赔总数'])]
    #[Assert\PositiveOrZero]
    private ?int $claimNum = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '理赔成功数'])]
    #[Assert\PositiveOrZero]
    private ?int $claimSuccessNum = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '当前保费'])]
    #[Assert\PositiveOrZero]
    private ?int $premium = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '当前账号余额'])]
    #[Assert\PositiveOrZero]
    private ?int $funds = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否不能投保'])]
    #[Assert\Type(type: 'bool')]
    private ?bool $needClose = null;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    public function getClaimNum(): ?int
    {
        return $this->claimNum;
    }

    public function setClaimNum(?int $claimNum): void
    {
        $this->claimNum = $claimNum;
    }

    public function getClaimSuccessNum(): ?int
    {
        return $this->claimSuccessNum;
    }

    public function setClaimSuccessNum(?int $claimSuccessNum): void
    {
        $this->claimSuccessNum = $claimSuccessNum;
    }

    public function getPremium(): ?int
    {
        return $this->premium;
    }

    public function setPremium(?int $premium): void
    {
        $this->premium = $premium;
    }

    public function getFunds(): ?int
    {
        return $this->funds;
    }

    public function setFunds(?int $funds): void
    {
        $this->funds = $funds;
    }

    public function isNeedClose(): ?bool
    {
        return $this->needClose;
    }

    public function setNeedClose(?bool $needClose): void
    {
        $this->needClose = $needClose;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
