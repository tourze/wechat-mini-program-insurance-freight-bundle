<?php

namespace WechatMiniProgramInsuranceFreightBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramInsuranceFreightBundle\Entity\Summary;

class SummaryTest extends TestCase
{
    public function testGettersAndSetters(): void
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