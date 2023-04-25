<?php

namespace App\Tests\Unit;
use App\Entity\Comment;
use App\Entity\ExploitedRessource;
use App\Entity\Favorite;
use App\Entity\Like;
use App\Entity\Notification;
use App\Entity\Relation;
use App\Entity\Ressource;
use App\Entity\Settings;
use App\Entity\User;
use Cassandra\Set;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testRemoveReceiver()
    {
        $receiver = new Relation();
        $user = new User();
        $user->addReceiver($receiver);
        $user->removeReceiver($receiver);
        $this->assertEmpty($user->getReceiver());
    }

    public function testGetUserIdentifier()
    {
        $user = new User();
        $this->assertIsString($user->getUserIdentifier());
    }

    public function testGetBirthday()
    {
        $user = new User();
        $user->setBirthday(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getBirthday());
    }

    public function test__construct()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
    }

    public function testGetUsername()
    {
        $user = new User();
        $user->setEmail('test_username');
        $this->assertEquals('test_username', $user->getUsername());
        $this->assertIsString($user->getUsername());
    }

    public function testIsVerified()
    {
        $user = new User();
        $user->setIsVerified(true);
        $this->assertTrue($user->isVerified());
    }

    public function testRemoveNotification()
    {
        $notification = new Notification();
        $user = new User();
        $user->addNotification($notification);
        $user->removeNotification($notification);
        $this->assertEmpty($user->getNotifications());
    }

    public function testGetNotifications()
    {
        $user = new User();
        $notification = new Notification();
        $user->addNotification($notification);
        $this->assertInstanceOf(Notification::class, $user->getNotifications()[0]);
    }

    public function testGetPassword()
    {
        $user = new User();
        $user->setPassword('test_password');
        $this->assertEquals('test_password', $user->getPassword());
        $this->assertIsString($user->getPassword());
    }

    public function testGetAccountName()
    {
        $user = new User();
        $user->setAccountName('test_accountName');
        $this->assertEquals('test_accountName', $user->getAccountName());
        $this->assertIsString($user->getAccountName());
    }

    public function testGetFavorites()
    {
        $user = new User();
        $favorite = new Favorite();
        $user->addFavorite($favorite);
        $this->assertInstanceOf(Favorite::class, $user->getFavorites()[0]);
    }

    public function testRemoveFavorite()
    {
        $favorite = new Favorite();
        $user = new User();
        $user->addFavorite($favorite);
        $user->removeFavorite($favorite);
        $this->assertEmpty($user->getFavorites());
    }

    public function testGetSettings()
    {
        $user = new User();
        $setting = new Settings();
        $user->setSettings($setting);
        $this->assertInstanceOf(Settings::class, $user->getSettings());
    }

    public function testAddReceiver()
    {
        $receiver = new Relation();
        $user = new User();
        $user->addReceiver($receiver);
        $this->assertContains($receiver, $user->getReceiver());
    }

    public function testAddNotification()
    {
        $notification = new Notification();
        $user = new User();
        $user->addNotification($notification);
        $this->assertContains($notification, $user->getNotifications());
    }

    public function testSetEmail()
    {
        $user = new User();
        $user->setEmail('test_email');
        $this->assertEquals('test_email', $user->getEmail());
        $this->assertIsString($user->getEmail());
    }

    public function testGetReceivedRelation()
    {
        $user = new User();
        $user->addReceivedRelation(new Relation());
        $this->assertInstanceOf(Relation::class, $user->getReceivedRelation()[0]);
    }

    public function testSetLastName()
    {
        $user = new User();
        $user->setLastName('test_lastName');
        $this->assertEquals('test_lastName', $user->getLastName());
        $this->assertIsString($user->getLastName());
    }

    public function testSetAccountName()
    {
        $user = new User();
        $user->setAccountName('test_accountName');
        $this->assertEquals('test_accountName', $user->getAccountName());
        $this->assertIsString($user->getAccountName());
    }

    public function testRemoveExploitedRessource()
    {
        $user = new User();
        $exploitedResource = new ExploitedRessource();
        $user->addExploitedRessource($exploitedResource);
        $user->removeExploitedRessource($exploitedResource);
        $this->assertEmpty($user->getExploitedRessources());
    }

    public function testAddFavorite()
    {
        $favorite = new Favorite();
        $user = new User();
        $user->addFavorite($favorite);
        $this->assertContains($favorite, $user->getFavorites());
    }

    public function testGetRoles()
    {
        $user = new User();
        $this->assertIsArray($user->getRoles());
    }

    public function testSetCreatedAt()
    {
        $user = new User();
        $user->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
    }

    public function testGetRessources()
    {
        $user = new User();
        $resource = new Ressource();
        $user->addRessource($resource);
        $this->assertContains($resource, $user->getRessources());
        $this->assertInstanceOf(Ressource::class, $user->getRessources()[0]);
    }

    public function testAddLike()
    {
        $like = new Like();
        $user = new User();
        $user->addLike($like);
        $this->assertContains($like, $user->getLikes());
    }

    public function testIsIsActive()
    {
        $user = new User();
        $user->setIsActive(true);
        $this->assertTrue($user->isIsActive());
    }

    public function testRemoveReceivedRelation()
    {
        $user = new User();
        $relation = new Relation();
        $user->addReceivedRelation($relation);
        $user->removeReceivedRelation($relation);
        $this->assertEmpty($user->getReceivedRelation());
    }

    public function testSetSettings()
    {
        $user = new User();
        $setting = new Settings();
        $user->setSettings($setting);
        $this->assertInstanceOf(Settings::class, $user->getSettings());
    }

    public function testSetFirstName()
    {
        $user = new User();
        $user->setFirstName('test_firstName');
        $this->assertEquals('test_firstName', $user->getFirstName());
        $this->assertIsString($user->getFirstName());
    }

    public function testSetBirthday()
    {
        $user = new User();
        $user->setBirthday(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getBirthday());
    }

    public function test__toString()
    {
        $user = new User();
        $user->setAccountName('test_accountName');
        $this->assertEquals('test_accountName', $user->__toString());
    }

    public function testSetPassword()
    {
        $user = new User();
        $user->setPassword('test_password');
        $this->assertEquals('test_password', $user->getPassword());
        $this->assertIsString($user->getPassword());
    }

    public function testGetLastName()
    {
        $user = new User();
        $user->setLastName('test_lastName');
        $this->assertEquals('test_lastName', $user->getLastName());
        $this->assertIsString($user->getLastName());
    }

    public function testAddRessource()
    {
        $resource = new Ressource();
        $user = new User();
        $user->addRessource($resource);
        $this->assertContains($resource, $user->getRessources());
    }

    public function testGetReceiver()
    {
        $user = new User();
        $user->addReceiver(new Relation());
        $this->assertInstanceOf(Relation::class, $user->getReceiver()[0]);
    }

    public function testGetEmail()
    {
        $user = new User();
        $user->setEmail('test_email');
        $this->assertEquals('test_email', $user->getEmail());
        $this->assertIsString($user->getEmail());
    }

    public function testSetRoles()
    {
        $user = new User();
        $user->setRoles(['test_role']);
        $this->assertContains('test_role', $user->getRoles());
    }

    public function testGetCreatedAt()
    {
        $user = new User();
        $user->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
    }

    public function testAddComment()
    {
        $comment = new Comment();
        $user = new User();
        $user->addComment($comment);
        $this->assertInstanceOf(Comment::class, $user->getComments()[0]);
    }

    public function testRemoveRessource()
    {
        $user = new User();
        $resource = new Ressource();
        $user->addRessource($resource);
        $user->removeRessource($resource);
        $this->assertEmpty($user->getRessources());
    }

    public function testAddExploitedRessource()
    {
        $user = new User();
        $exploitedResource = new ExploitedRessource();
        $user->addExploitedRessource($exploitedResource);
        $this->assertInstanceOf(ExploitedRessource::class, $user->getExploitedRessources()[0]);
    }

    public function testRemoveComment()
    {
        $user = new User();
        $comment = new Comment();
        $user->addComment($comment);
        $user->removeComment($comment);
        $this->assertEmpty($user->getComments());
    }

    public function testAddReceivedRelation()
    {
        $user = new User();
        $relation = new Relation();
        $user->addReceivedRelation($relation);
        $this->assertInstanceOf(Relation::class, $user->getReceivedRelation()[0]);
    }


    public function testGetLikes()
    {
        $user = new User();
        $like = new Like();
        $user->addLike($like);
        $this->assertContains($like, $user->getLikes());
        $this->assertInstanceOf(Like::class, $user->getLikes()[0]);
    }

    public function testSetIsVerified()
    {
        $user = new User();
        $user->setIsVerified(true);
        $this->assertTrue($user->isVerified());
    }

    public function testSetIsActive()
    {
        $user = new User();
        $user->setIsActive(true);
        $this->assertTrue($user->isIsActive());
    }

    public function testRemoveLike()
    {
        $user = new User();
        $like = new Like();
        $user->addLike($like);
        $user->removeLike($like);
        $this->assertEmpty($user->getLikes());
    }

    public function testGetComments()
    {
        $user = new User();
        $comment = new Comment();
        $user->addComment($comment);
        $this->assertContains($comment, $user->getComments());
        $this->assertInstanceOf(Comment::class, $user->getComments()[0]);
    }

    public function testGetExploitedRessources()
    {
        $user = new User();
        $exploitedResource = new ExploitedRessource();
        $user->addExploitedRessource($exploitedResource);
        $this->assertContains($exploitedResource, $user->getExploitedRessources());
        $this->assertInstanceOf(ExploitedRessource::class, $user->getExploitedRessources()[0]);
    }

    public function testGetFirstName()
    {
        $user = new User();
        $user->setFirstName('test_firstName');
        $this->assertEquals('test_firstName', $user->getFirstName());
        $this->assertIsString($user->getFirstName());
    }
}
