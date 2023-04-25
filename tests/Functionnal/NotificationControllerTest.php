<?php

namespace App\Tests\Functionnal;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NotificationControllerTest extends WebTestCase
{
    public function testGetUserNotifications() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/notifications');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());

    }
}
