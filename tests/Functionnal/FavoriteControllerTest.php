<?php

namespace App\Tests\Functionnal;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FavoriteControllerTest extends WebTestCase
{
    public function testGetAllFavorite() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/favorite');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateFavorite() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $data = [
            'ressource_id' => 1,
        ];

        $json_data = json_encode($data);

        $client->request('POST', '/api/favorite', [], [], array('CONTENT_TYPE' => 'application/json'), $json_data);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testDeleteFavorite() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('DELETE', '/api/favorite/1');
        $this->assertResponseStatusCodeSame(204);
    }
}
