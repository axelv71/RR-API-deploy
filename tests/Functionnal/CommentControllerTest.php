<?php

namespace App\Tests\Functionnal;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase
{
    /**
     * @throws \Exception
     */
    public function testGetOneComment() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/comments/3');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    /**
     * @throws \Exception
     */
    public function testDeleteComment(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('DELETE', '/api/comments/3');
        $this->assertResponseStatusCodeSame(204);
    }

    public function testAddComment(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $data = [
            'content' => 'test',
            'ressourceid' => 1,
        ];

        $json_data = json_encode($data);

        $client->request('POST', '/api/comments', [], [], array('CONTENT_TYPE' => 'application/json'), $json_data);

        $this->assertResponseStatusCodeSame(201);
    }



}
