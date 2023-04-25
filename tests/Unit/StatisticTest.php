<?php

namespace App\Tests\Unit;
use App\Entity\Category;
use App\Entity\RelationType;
use App\Entity\RessourceType;
use App\Entity\Statistic;
use App\Entity\StatisticType;
use PHPUnit\Framework\TestCase;

class StatisticTest extends TestCase
{

    public function testSetType()
    {
        $statistic = new Statistic();
        $statisticType = new StatisticType();
        $statistic->setType($statisticType);
        $this->assertInstanceOf(StatisticType::class, $statistic->getType());

    }

    public function testGetCreatedAt()
    {
        $statistic = new Statistic();
        $statistic->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $statistic->getCreatedAt());
    }

    public function testCreate()
    {
        $statisticType = new StatisticType();
        $relationType = new RelationType();
        $ressourceType = new RessourceType();
        $category = new Category();

        $statistic = Statistic::create($statisticType, $relationType, $ressourceType, $category);
        $this->assertInstanceOf(Statistic::class, $statistic);
    }

    public function testGetRelationType()
    {
        $statistic = new Statistic();
        $relationType = new RelationType();
        $statistic->setRelationType($relationType);
        $this->assertInstanceOf(RelationType::class, $statistic->getRelationType());
    }

    public function testSetRessourceType()
    {
        $statistic = new Statistic();
        $ressourceType = new RessourceType();
        $statistic->setRessourceType($ressourceType);
        $this->assertInstanceOf(RessourceType::class, $statistic->getRessourceType());
    }

    public function testSetCategory()
    {
        $statistic = new Statistic();
        $category = new Category();
        $statistic->setCategory($category);
        $this->assertInstanceOf(Category::class, $statistic->getCategory());
    }

    public function testSetCreatedAt()
    {
        $statistic = new Statistic();
        $statistic->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $statistic->getCreatedAt());
    }

    public function testSetRelationType()
    {
        $statistic = new Statistic();
        $relationType = new RelationType();
        $statistic->setRelationType($relationType);
        $this->assertInstanceOf(RelationType::class, $statistic->getRelationType());
    }

    public function testGetRessourceType()
    {
        $statistic = new Statistic();
        $ressourceType = new RessourceType();
        $statistic->setRessourceType($ressourceType);
        $this->assertInstanceOf(RessourceType::class, $statistic->getRessourceType());
    }

    public function testGetType()
    {
        $statistic = new Statistic();
        $statisticType = new StatisticType();
        $statistic->setType($statisticType);
        $this->assertInstanceOf(StatisticType::class, $statistic->getType());
    }

    public function testGetCategory()
    {
        $statistic = new Statistic();
        $category = new Category();
        $statistic->setCategory($category);
        $this->assertInstanceOf(Category::class, $statistic->getCategory());
    }
}
