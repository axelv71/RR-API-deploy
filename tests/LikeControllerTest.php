<?php


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LikeControllerTest extends WebTestCase
{
    public function testGetAllLike() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/like');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateLike() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $data = [
            'ressource_id' => 1,
        ];

        $json_data = json_encode($data);

        $client->request('POST', '/api/like', [], [], array('CONTENT_TYPE' => 'application/json'), $json_data);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testDeleteLike() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@gmail.com');
        $client->loginUser($testUser);

        $client->request('DELETE', '/api/like/1');
        $this->assertResponseStatusCodeSame(204);
    }
}
