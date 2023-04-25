<?php

namespace App\Tests\Functionnal;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RelationControllerTest extends WebTestCase
{
    public function testGetOneRelation() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/relations/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testAddRelation() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $data = [
            "relationType" => 1,
            "receiver" => 1,
        ];

        $json_data = json_encode($data);

        $client->request('POST', '/api/relation/add', [], [], array('CONTENT_TYPE' => 'application/json'), $json_data);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testGetUserRelations() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/relations');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetUserRelationByRelationType() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/relations/1/relationtype');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testAcceptRelation() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('POST', '/api/relation/1');
        $this->assertResponseStatusCodeSame(200 || 401);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteRelation() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('DELETE', '/api/relations/1');
        $this->assertResponseStatusCodeSame(200 || 401);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

}
