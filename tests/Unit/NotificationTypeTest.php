<?php

namespace App\Tests\Unit;
use App\Entity\Notification;
use App\Entity\NotificationType;
use PHPUnit\Framework\TestCase;

class NotificationTypeTest extends TestCase
{

    public function testGetNotifications()
    {
        $notification = new Notification();
        $notificationType = new NotificationType();
        $notificationType->addNotification($notification);
        $this->assertContains($notification, $notificationType->getNotifications());
    }

    public function testGetLabel()
    {
        $notificationType = new NotificationType();
        $notificationType->setLabel('test_label');
        $this->assertEquals('test_label', $notificationType->getLabel());
        $this->assertIsString($notificationType->getLabel());
    }

    public function testAddNotification()
    {
        $notification = new Notification();
        $notificationType = new NotificationType();
        $notificationType->addNotification($notification);
        $this->assertContains($notification, $notificationType->getNotifications());
    }

    public function testGetName()
    {
        $notificationType = new NotificationType();
        $notificationType->setName('test_name');
        $this->assertEquals('test_name', $notificationType->getName());
        $this->assertIsString($notificationType->getName());
    }

    public function testCreate()
    {
        $notificationType = NotificationType::create('test_label', 'test_name');
        $this->assertInstanceOf(NotificationType::class, $notificationType);
    }

    public function testSetName()
    {
        $notificationType = new NotificationType();
        $notificationType->setName('test_name');
        $this->assertEquals('test_name', $notificationType->getName());
        $this->assertIsString($notificationType->getName());
    }

    public function testSetLabel()
    {
        $notificationType = new NotificationType();
        $notificationType->setLabel('test_label');
        $this->assertEquals('test_label', $notificationType->getLabel());
        $this->assertIsString($notificationType->getLabel());
    }

    public function testRemoveNotification()
    {
        $notification = new Notification();
        $notificationType = new NotificationType();
        $notificationType->addNotification($notification);
        $notificationType->removeNotification($notification);
        $this->assertNotContains($notification, $notificationType->getNotifications());
    }

    public function test__construct()
    {
        $notificationType = new NotificationType();
        $this->assertInstanceOf(NotificationType::class, $notificationType);
    }
}
