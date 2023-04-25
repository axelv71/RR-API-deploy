<?php

namespace App\Tests\Unit;
use App\Entity\Statistic;
use App\Entity\StatisticType;
use PHPUnit\Framework\TestCase;

class StatisticTypeTest extends TestCase
{

    public function testGetStatistics()
    {
        $statisticType = new StatisticType();
        $statistic = new Statistic();
        $statisticType->addStatistic($statistic);
        $this->assertContains($statistic, $statisticType->getStatistics());
    }

    public function testSetName()
    {
        $statisticType = new StatisticType();
        $statisticType->setName('test_name');
        $this->assertEquals('test_name', $statisticType->getName());
        $this->assertIsString($statisticType->getName());
    }

    public function testAddStatistic()
    {
        $statistic = new Statistic();
        $statisticType = new StatisticType();
        $statisticType->addStatistic($statistic);
        $this->assertInstanceOf(Statistic::class, $statisticType->getStatistics()[0]);
    }

    public function testRemoveStatistic()
    {
        $statistic = new Statistic();
        $statisticType = new StatisticType();
        $statisticType->addStatistic($statistic);
        $statisticType->removeStatistic($statistic);
        $this->assertEmpty($statisticType->getStatistics());
    }

    public function test__construct()
    {
        $statistic = new Statistic();
        $this->assertInstanceOf(Statistic::class, $statistic);
    }

    public function testGetName()
    {
        $statisticType = new StatisticType();
        $statisticType->setName('test_name');
        $this->assertEquals('test_name', $statisticType->getName());
        $this->assertIsString($statisticType->getName());
    }

    public function testCreate()
    {
        $statisticType = StatisticType::create('test_name');
        $this->assertInstanceOf(StatisticType::class, $statisticType);
    }
}
