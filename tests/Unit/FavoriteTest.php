<?php

namespace App\Tests\Unit;

use App\Entity\Favorite;
use App\Entity\Ressource;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class FavoriteTest extends TestCase
{

    public function testGetRessourceFavorite()
    {
        $resource = new Ressource();
        $favorite = new Favorite();
        $favorite->setRessourceFavorite($resource);
        $this->assertInstanceOf(Ressource::class, $favorite->getRessourceFavorite());
    }

    public function testGetUserFavorite()
    {
        $user = new User();
        $favorite = new Favorite();
        $favorite->setUserFavorite($user);
        $this->assertInstanceOf(User::class, $favorite->getUserFavorite());
    }

    public function testSetUserFavorite()
    {
        $user = new User();
        $favorite = new Favorite();
        $favorite->setUserFavorite($user);
        $this->assertInstanceOf(User::class, $favorite->getUserFavorite());
    }

    public function testSetRessourceFavorite()
    {
        $resource  = new Ressource();
        $favorite = new Favorite();
        $favorite->setRessourceFavorite($resource);
        $this->assertInstanceOf(Ressource::class, $favorite->getRessourceFavorite());
    }
}
