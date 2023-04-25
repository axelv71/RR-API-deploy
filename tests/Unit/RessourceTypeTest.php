<?php

namespace App\Tests\Unit;
use App\Entity\Ressource;
use App\Entity\RessourceType;
use App\Entity\Statistic;
use PHPUnit\Framework\TestCase;

class RessourceTypeTest extends TestCase
{

    public function testGetName()
    {
        $ressourceType = new RessourceType();
        $ressourceType->setName('test_name');
        $this->assertEquals('test_name', $ressourceType->getName());
        $this->assertIsString($ressourceType->getName());
    }

    public function testRemoveRessource()
    {
        $resource = new Ressource();
        $ressourceType = new RessourceType();
        $ressourceType->addRessource($resource);
        $ressourceType->removeRessource($resource);
        $this->assertNotContains($resource, $ressourceType->getRessources());
    }

    public function testRemoveStatistic()
    {
        $statistics = new Statistic();
        $ressourceType = new RessourceType();
        $ressourceType->addStatistic($statistics);
        $ressourceType->removeStatistic($statistics);
        $this->assertNotContains($statistics, $ressourceType->getStatistics());
    }

    public function testGetRessources()
    {
        $resource = new Ressource();
        $ressourceType = new RessourceType();
        $ressourceType->addRessource($resource);
        $this->assertContains($resource, $ressourceType->getRessources());
    }

    public function test__construct()
    {
        $ressourceType = new RessourceType();
        $this->assertInstanceOf(RessourceType::class, $ressourceType);
    }

    public function testSetName()
    {
        $ressourceType = new RessourceType();
        $ressourceType->setName('test_name');
        $this->assertEquals('test_name', $ressourceType->getName());
        $this->assertIsString($ressourceType->getName());
    }

    public function testGetStatistics()
    {
        $statistics = new Statistic();
        $ressourceType = new RessourceType();
        $ressourceType->addStatistic($statistics);
        $this->assertContains($statistics, $ressourceType->getStatistics());
    }

    public function testCreate()
    {
        $resourceType = RessourceType::create('test_name');
        $this->assertInstanceOf(RessourceType::class, $resourceType);
    }

    public function testAddRessource()
    {
        $resource = new Ressource();
        $ressourceType = new RessourceType();
        $ressourceType->addRessource($resource);
        $this->assertContains($resource, $ressourceType->getRessources());
    }

    public function testAddStatistic()
    {
        $statistics = new Statistic();
        $ressourceType = new RessourceType();
        $ressourceType->addStatistic($statistics);
        $this->assertContains($statistics, $ressourceType->getStatistics());
    }
}
