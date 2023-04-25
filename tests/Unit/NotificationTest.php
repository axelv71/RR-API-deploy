<?php

namespace App\Tests\Unit;
use App\Entity\Notification;
use App\Entity\NotificationType;
use App\Entity\Relation;
use App\Entity\Ressource;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{

    public function testCreate()
    {
        $sender = new User();
        $receiver = new User();
        $content = 'test_content';
        $resource = new Ressource();
        $relation = new Relation();
        $notificationType = new NotificationType();

        $notification = Notification::create($sender, $receiver, $notificationType, $content, $resource, $relation);
        $this->assertInstanceOf(Notification::class, $notification);
    }

    public function testSetNotificationType()
    {
        $notification = new Notification();
        $notificationType = new NotificationType();
        $notification->setNotificationType($notificationType);
        $this->assertInstanceOf(NotificationType::class, $notification->getNotificationType());
    }

    public function testSetSender()
    {
        $sender = new User();
        $notification = new Notification();
        $notification->setSender($sender);
        $this->assertInstanceOf(User::class, $notification->getSender());
    }

    public function testSetRelation()
    {
        $relation = new Relation();
        $notification = new Notification();
        $notification->setRelation($relation);
        $this->assertInstanceOf(Relation::class, $notification->getRelation());
    }

    public function testSetResource()
    {
        $resource = new Ressource();
        $notification = new Notification();
        $notification->setResource($resource);
        $this->assertInstanceOf(Ressource::class, $notification->getResource());
    }

    public function testGetResource()
    {
        $resource = new Ressource();
        $notification = new Notification();
        $notification->setResource($resource);
        $this->assertInstanceOf(Ressource::class, $notification->getResource());
    }

    public function testGetNotificationType()
    {
        $notificationType = new NotificationType();
        $notification = new Notification();
        $notification->setNotificationType($notificationType);
        $this->assertInstanceOf(NotificationType::class, $notification->getNotificationType());
    }

    public function testSetReceiver()
    {
        $receiver = new User();
        $notification = new Notification();
        $notification->setReceiver($receiver);
        $this->assertInstanceOf(User::class, $notification->getReceiver());
    }

    public function testGetSender()
    {
        $sender = new User();
        $notification = new Notification();
        $notification->setSender($sender);
        $this->assertInstanceOf(User::class, $notification->getSender());
    }

    public function test__construct()
    {
        $notification = new Notification();
        $this->assertInstanceOf(Notification::class, $notification);
    }

    public function testSetContent()
    {
        $notification = new Notification();
        $content = 'test_content';
        $notification->setContent($content);
        $this->assertEquals($content, $notification->getContent());
        $this->assertIsString($notification->getContent());
    }

    public function testSetCreatedAt()
    {
        $notification = new Notification();
        $createdAt = new \DateTimeImmutable();
        $notification->setCreatedAt($createdAt);
        $this->assertInstanceOf(\DateTimeImmutable::class, $notification->getCreatedAt());
    }

    public function testGetReceiver()
    {
        $receiver = new User();
        $notification = new Notification();
        $notification->setReceiver($receiver);
        $this->assertInstanceOf(User::class, $notification->getReceiver());
    }

    public function testGetContent()
    {
        $notification = new Notification();
        $content = 'test_content';
        $notification->setContent($content);
        $this->assertEquals($content, $notification->getContent());
        $this->assertIsString($notification->getContent());
    }

    public function testGetCreatedAt()
    {
        $notification = new Notification();
        $createdAt = new \DateTimeImmutable();
        $notification->setCreatedAt($createdAt);
        $this->assertInstanceOf(\DateTimeImmutable::class, $notification->getCreatedAt());
    }

    public function testGetRelation()
    {
        $relation = new Relation();
        $notification = new Notification();
        $notification->setRelation($relation);
        $this->assertInstanceOf(Relation::class, $notification->getRelation());
    }
}
