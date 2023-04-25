<?php

namespace App\Tests\Unit;
use App\Entity\Relation;
use App\Entity\RelationType;
use App\Entity\Ressource;
use App\Entity\Statistic;
use PHPUnit\Framework\TestCase;

class RelationTypeTest extends TestCase
{

    public function testAddRelation()
    {
        $relation = new Relation();
        $relationType = new RelationType();
        $relationType->addRelation($relation);
        $this->assertInstanceOf(Relation::class, $relationType->getRelations()->first());
    }

    public function testAddStatistic()
    {
        $statistic = new Statistic();
        $relationType = new RelationType();
        $relationType->addStatistic($statistic);
        $this->assertInstanceOf(Statistic::class, $relationType->getStatistics()[0]);
    }

    public function testRemoveStatistic()
    {
        $statistic = new Statistic();
        $relationType = new RelationType();
        $relationType->addStatistic($statistic);
        $relationType->removeStatistic($statistic);
        $this->assertEmpty($relationType->getStatistics());
    }

    public function testRemoveRessource()
    {
        $resource = new Ressource();
        $relationType = new RelationType();
        $relationType->addRessource($resource);
        $relationType->removeRessource($resource);
        $this->assertEmpty($relationType->getRessources());
    }

    public function testGetLabel()
    {
        $relationType = new RelationType();
        $relationType->setLabel('test_label');
        $this->assertEquals('test_label', $relationType->getLabel());
        $this->assertIsString($relationType->getLabel());
    }

    public function testGetCreatedAt()
    {
        $relationType = new RelationType();
        $relationType->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $relationType->getCreatedAt());
    }

    public function testSetCreatedAt()
    {
        $relationType = new RelationType();
        $relationType->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $relationType->getCreatedAt());
    }

    public function testSetLabel()
    {
        $relationType = new RelationType();
        $relationType->setLabel('test_label');
        $this->assertEquals('test_label', $relationType->getLabel());
        $this->assertIsString($relationType->getLabel());
    }

    public function testGetStatistics()
    {
        $statistic = new Statistic();
        $relationType = new RelationType();
        $relationType->addStatistic($statistic);
        $this->assertInstanceOf(Statistic::class, $relationType->getStatistics()->first());
    }

    public function testGetName()
    {
        $relationType = new RelationType();
        $relationType->setName('test_name');
        $this->assertEquals('test_name', $relationType->getName());
        $this->assertIsString($relationType->getName());
    }

    public function testGetRessources()
    {
        $resource = new Ressource();
        $relationType = new RelationType();
        $relationType->addRessource($resource);
        $this->assertInstanceOf(Ressource::class, $relationType->getRessources()->first());
    }

    public function testSetName()
    {
        $relationType = new RelationType();
        $relationType->setName('test_name');
        $this->assertEquals('test_name', $relationType->getName());
        $this->assertIsString($relationType->getName());
    }

    public function test__construct()
    {
        $relationType = new RelationType();
        $this->assertInstanceOf(RelationType::class, $relationType);
    }

    public function testRemoveRelation()
    {
        $relation = new Relation();
        $relationType = new RelationType();
        $relationType->addRelation($relation);
        $relationType->removeRelation($relation);
        $this->assertEmpty($relationType->getRelations());
    }

    public function testSetId()
    {
        $relationType = new RelationType();
        $relationType->setId(1);
        $this->assertEquals(1, $relationType->getId());
        $this->assertIsInt($relationType->getId());
    }

    public function testCreate()
    {
        $relationType = RelationType::create('test_label', 'test_name');
        $this->assertEquals('test_label', $relationType->getLabel());
        $this->assertEquals('test_name', $relationType->getName());
        $this->assertIsString($relationType->getLabel());
        $this->assertIsString($relationType->getName());
    }

    public function testGetRelations()
    {
        $relation = new Relation();
        $relationType = new RelationType();
        $relationType->addRelation($relation);
        $this->assertInstanceOf(Relation::class, $relationType->getRelations()->first());
    }

    public function testAddRessource()
    {
        $resource = new Ressource();
        $relationType = new RelationType();
        $relationType->addRessource($resource);
        $this->assertInstanceOf(Ressource::class, $relationType->getRessources()->first());
    }
}
