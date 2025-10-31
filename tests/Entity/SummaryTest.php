<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;

/**
 * @internal
 */
#[CoversClass(Summary::class)]
final class SummaryTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new Summary();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            // Summary 实体没有 setId 方法，id 属性由数据库自动生成
            'date' => ['date', new \DateTimeImmutable()],
            'total' => ['total', 5],
            'claimNum' => ['claimNum', 2],
            'claimSuccessNum' => ['claimSuccessNum', 1],
            'premium' => ['premium', 1000],
            'funds' => ['funds', 5000],
            'needClose' => ['needClose', true],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSummarySpecificSetters(): void
    {
        $summary = new Summary();

        $date = new \DateTimeImmutable();
        $summary->setDate($date);
        $this->assertEquals($date, $summary->getDate());

        $summary->setTotal(5);
        $this->assertEquals(5, $summary->getTotal());

        $summary->setClaimNum(2);
        $this->assertEquals(2, $summary->getClaimNum());

        $summary->setClaimSuccessNum(1);
        $this->assertEquals(1, $summary->getClaimSuccessNum());

        $summary->setPremium(1000);
        $this->assertEquals(1000, $summary->getPremium());

        $summary->setFunds(5000);
        $this->assertEquals(5000, $summary->getFunds());

        $summary->setNeedClose(true);
        $this->assertTrue($summary->isNeedClose());
    }
}
