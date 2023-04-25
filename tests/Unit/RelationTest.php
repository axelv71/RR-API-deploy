<?php

namespace App\Tests\Unit;
use App\Entity\Notification;
use App\Entity\Relation;
use App\Entity\RelationType;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class RelationTest extends TestCase
{

    public function testSetSender()
    {
        $user = new User();
        $relation = new Relation();
        $relation->setSender($user);
        $this->assertInstanceOf(User::class, $relation->getSender());
    }

    public function testSetRelationType()
    {
        $relationType = new RelationType();
        $relation = new Relation();
        $relation->setRelationType($relationType);
        $this->assertInstanceOf(RelationType::class, $relation->getRelationType());
    }

    public function testSetUpdatedAt()
    {
        $relation = new Relation();
        $relation->setUpdatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $relation->getUpdatedAt());
    }

    public function testCreate()
    {
        $sender = new User();
        $receiver = new User();
        $relationType = new RelationType();
        $relation = Relation::create($sender, $receiver, $relationType);
        $this->assertInstanceOf(Relation::class, $relation);
    }

    public function test__construct()
    {
        $relation = new Relation();
        $this->assertInstanceOf(Relation::class, $relation);
    }

    public function testGetCreatedAt()
    {
        $relation = new Relation();
        $this->assertInstanceOf(\DateTimeImmutable::class, $relation->getCreatedAt());
    }

    public function testSetCreatedAt()
    {
        $relation = new Relation();
        $relation->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $relation->getCreatedAt());
    }

    public function testSetReceiver()
    {
        $user = new User();
        $relation = new Relation();
        $relation->setReceiver($user);
        $this->assertInstanceOf(User::class, $relation->getReceiver());
    }

    public function testGetUpdatedAt()
    {
        $relation = new Relation();
        $this->assertInstanceOf(\DateTimeImmutable::class, $relation->getUpdatedAt());
    }

    public function testGetSender()
    {
        $relation = new Relation();
        $sender = new User();
        $relation->setSender($sender);
        $this->assertInstanceOf(User::class, $relation->getSender());
    }

    public function testAddNotification()
    {
        $notification = new Notification();
        $relation = new Relation();
        $relation->addNotification($notification);
        $this->assertContains($notification, $relation->getNotifications());
    }

    public function testRemoveNotification()
    {
        $notification = new Notification();
        $relation = new Relation();
        $relation->addNotification($notification);
        $relation->removeNotification($notification);
        $this->assertNotContains($notification, $relation->getNotifications());
    }

    public function testIsIsAccepted()
    {
        $relation = new Relation();
        $relation->setIsAccepted(true);
        $this->assertTrue($relation->isIsAccepted());
        $this->assertIsBool($relation->isIsAccepted());
    }

    public function testSetIsAccepted()
    {
        $relation = new Relation();
        $relation->setIsAccepted(true);
        $this->assertTrue($relation->isIsAccepted());
        $this->assertIsBool($relation->isIsAccepted());
    }

    public function testGetRelationType()
    {
        $relationType = new RelationType();
        $relation = new Relation();
        $relation->setRelationType($relationType);
        $this->assertInstanceOf(RelationType::class, $relation->getRelationType());
    }

    public function testGetReceiver()
    {
        $receiver = new User();
        $relation = new Relation();
        $relation->setReceiver($receiver);
        $this->assertInstanceOf(User::class, $relation->getReceiver());
    }
}
