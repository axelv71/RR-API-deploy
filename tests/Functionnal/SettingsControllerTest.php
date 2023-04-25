<?php

namespace App\Tests\Functionnal;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SettingsControllerTest extends WebTestCase
{
    public function testGetUserSettings(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/settings/user');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetAllThemes(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/settings/themes');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetAllLanguages(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/settings/languages');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateUserSettings(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $data = [
            'theme' => 1,
            'language' => 1,
            'isDark' => true,
            'allowNotifications' => true,
            'useDeviceMode' => true,
        ];

        $client->request('PUT', '/api/settings/update', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetOneSetting() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/settings/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }
}
