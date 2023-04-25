<?php

namespace App\Tests\Unit;

use App\Entity\Like;
use App\Entity\Ressource;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class LikeTest extends TestCase
{

    public function testGetUserLike()
    {
        $user = new User();
        $like = new Like();
        $like->setUserLike($user);
        $this->assertInstanceOf(User::class, $like->getUserLike());
    }

    public function testSetIsLiked()
    {
        $like = new Like();
        $like->setIsLiked(true);
        $this->assertTrue($like->isIsLiked());
        $this->assertIsBool($like->isIsLiked());
    }

    public function testIsIsLiked()
    {
        $like = new Like();
        $like->setIsLiked(true);
        $this->assertTrue($like->isIsLiked());
        $this->assertIsBool($like->isIsLiked());
    }

    public function testSetUserLike()
    {
        $user = new User();
        $like = new Like();
        $like->setUserLike($user);
        $this->assertInstanceOf(User::class, $like->getUserLike());
    }

    public function testGetRessourceLike()
    {
        $like = new Like();
        $this->assertNull($like->getRessourceLike());
    }

    public function testSetRessourceLike()
    {
        $resource = new Ressource();
        $like = new Like();
        $like->setRessourceLike($resource);
        $this->assertInstanceOf(Ressource::class, $like->getRessourceLike());
    }
}
