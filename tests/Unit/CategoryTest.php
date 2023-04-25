<?php

namespace App\Tests\Unit;

use App\Entity\Category;
use App\Entity\Ressource;
use App\Entity\Statistic;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    public function test__construct()
    {
        $category = new Category();
        $this->assertInstanceOf(Category::class, $category);
    }

    public function testAddStatistic()
    {
        $statistic = new Statistic();
        $category = new Category();
        $category->addStatistic($statistic);
    }

    public function testRemoveStatistic()
    {
        $statistic = new Statistic();
        $category = new Category();
        $category->addStatistic($statistic);
        $category->removeStatistic($statistic);
    }

    public function testSetLabel()
    {
        $category = new Category();
        $category->setLabel('test_label');
        $this->assertEquals('test_label', $category->getLabel());
        $this->assertIsString($category->getLabel());
    }

    public function testSetName()
    {
        $category = new Category();
        $category->setName('test_name');
        $this->assertEquals('test_name', $category->getName());
        $this->assertIsString($category->getName());
    }

    public function testSetId()
    {
        $category = new Category();
        $category->setId(1);
        $this->assertEquals(1, $category->getId());
        $this->assertIsInt($category->getId());
    }

    public function testGetCreatedAt()
    {
        $category = new Category();
        $this->assertInstanceOf(\DateTimeImmutable::class, $category->getCreatedAt());
    }

    public function testGetRessources()
    {
        $category = new Category();
        $resource = new Ressource();
        $category->addRessource($resource);
        $category_resource = $category->getRessources();
        $this->assertInstanceOf(Ressource::class, $category_resource[0]);
    }

    public function testAddRessource()
    {
        $category = new Category();
        $resource = new Ressource();
        $category->addRessource($resource);
        $this->assertInstanceOf(Ressource::class, $category->getRessources()[0]);
    }

    public function testRemoveRessource()
    {
        $category = new Category();
        $resource = new Ressource();
        $category->addRessource($resource);
        $category->removeRessource($resource);
        $this->assertEmpty($category->getRessources());
    }

    public function testSetCreatedAt()
    {
        $category = new Category();
        $category->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $category->getCreatedAt());
    }

    public function testCreate()
    {
        $category = Category::create('label', 'name');
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('label', $category->getLabel());
        $this->assertEquals('name', $category->getName());
        $this->assertInstanceOf(\DateTimeImmutable::class, $category->getCreatedAt());
        $this->assertIsString($category->getName());
        $this->assertIsString($category->getLabel());
    }

    public function test__toString()
    {
        $category = new Category();
        $category->setLabel('test_label');
        $this->assertEquals('test_label', $category->__toString());
    }

    public function testGetName()
    {
        $category = new Category();
        $category->setName('test_name');
        $this->assertEquals('test_name', $category->getName());
        $this->assertIsString($category->getName());
    }

    public function testGetLabel()
    {
        $category = new Category();
        $category->setLabel('test_label');
        $this->assertEquals('test_label', $category->getLabel());
        $this->assertIsString($category->getLabel());
    }

    public function testGetId()
    {
        $category = new Category();
        $category->setId(1);
        $this->assertEquals(1, $category->getId());
        $this->assertIsInt($category->getId());
    }

    public function testGetStatistics()
    {
        $category = new Category();
        $statistic = new Statistic();
        $category->addStatistic($statistic);
        $category_statistic = $category->getStatistics();
        $this->assertInstanceOf(Statistic::class, $category_statistic[0]);
    }
}
