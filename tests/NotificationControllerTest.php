<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NotificationControllerTest extends WebTestCase
{
    public function testGetUserNotifications() : void
    {
        $client = static::createClient();
        $client->request('GET', '/api/notifications');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());

        $client->request('GET', '/api/notifications');
    }
}
