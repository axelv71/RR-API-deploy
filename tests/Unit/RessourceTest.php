<?php

namespace App\Tests\Unit;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\ExploitedRessource;
use App\Entity\Favorite;
use App\Entity\Like;
use App\Entity\Media;
use App\Entity\Notification;
use App\Entity\RelationType;
use App\Entity\Ressource;
use App\Entity\RessourceType;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class RessourceTest extends TestCase
{

    public function testSetIsPublished()
    {
        $resource = new Ressource();
        $resource->setIsPublished(true);
        $this->assertTrue($resource->isIsPublished());
        $this->assertIsBool($resource->isIsPublished());

    }

    public function testGetMedia()
    {
        $media = new Media();
        $resource = new Ressource();
        $resource->addMedia($media);
        $this->assertInstanceOf(Media::class, $resource->getMedia()[0]);
    }

    public function testSetIsFavorite()
    {
        $resource = new Ressource();
        $resource->setIsFavorite(true);
        $this->assertTrue($resource->getIsFavorite());
        $this->assertIsBool($resource->getIsFavorite());
    }

    public function testGetCreator()
    {
        $user = new User();
        $resource = new Ressource();
        $resource->setCreator($user);
        $this->assertInstanceOf(User::class, $resource->getCreator());
    }

    public function testSetTitle()
    {
        $resource = new Ressource();
        $resource->setTitle('test_title');
        $this->assertEquals('test_title', $resource->getTitle());
        $this->assertIsString($resource->getTitle());
    }

    public function testGetType()
    {
        $resourceType = new RessourceType();
        $resource = new Ressource();
        $resource->setType($resourceType);
        $this->assertInstanceOf(RessourceType::class, $resource->getType());

    }

    public function testAddLike()
    {
        $like = new Like();
        $resource = new Ressource();
        $resource->addLike($like);
        $this->assertInstanceOf(Like::class, $resource->getLikes()[0]);
    }

    public function testGetLikes()
    {
        $like = new Like();
        $resource = new Ressource();
        $resource->addLike($like);
        $this->assertInstanceOf(Like::class, $resource->getLikes()[0]);
    }

    public function testGetCategory()
    {
        $resource = new Ressource();
        $category = new Category();
        $resource->setCategory($category);
        $this->assertInstanceOf(Category::class, $resource->getCategory());
    }

    public function testRemoveFavorite()
    {
        $favorite = new Favorite();
        $resource = new Ressource();
        $resource->addFavorite($favorite);
        $resource->removeFavorite($favorite);
        $this->assertEmpty($resource->getFavorites());
    }

    public function testAddExploitedRessource()
    {
        $resource = new Ressource();
        $exploited = new ExploitedRessource();
        $resource->addExploitedRessource($exploited);
        $this->assertInstanceOf(ExploitedRessource::class, $resource->getExploitedRessources()[0]);
    }

    public function testRemoveLike()
    {
        $like = new Like();
        $resource = new Ressource();
        $resource->addLike($like);
        $resource->removeLike($like);
        $this->assertEmpty($resource->getLikes());
    }

    public function testIsIsValid()
    {
        $resource = new Ressource();
        $resource->setIsValid(true);
        $this->assertTrue($resource->isIsValid());
        $this->assertIsBool($resource->isIsValid());
    }

    public function testGetIsLiked()
    {
        $resource = new Ressource();
        $resource->setIsLiked(true);
        $this->assertTrue($resource->getIsLiked());
        $this->assertIsBool($resource->getIsLiked());
    }

    public function testRemoveExploitedRessource()
    {
        $resource = new Ressource();
        $exploited = new ExploitedRessource();
        $resource->addExploitedRessource($exploited);
        $resource->removeExploitedRessource($exploited);
        $this->assertEmpty($resource->getExploitedRessources());
    }

    public function testSetDescription()
    {
        $resource = new Ressource();
        $resource->setDescription('test_description');
        $this->assertEquals('test_description', $resource->getDescription());
        $this->assertIsString($resource->getDescription());
    }

    public function testGetFavorites()
    {
        $favorite = new Favorite();
        $resource = new Ressource();
        $resource->addFavorite($favorite);
        $this->assertInstanceOf(Favorite::class, $resource->getFavorites()[0]);
    }

    public function testGetRelationType()
    {
        $resource = new Ressource();
        $relationType = new RelationType();
        $resource->addRelationType($relationType);
        $this->assertInstanceOf(RelationType::class, $resource->getRelationType()[0]);
    }

    public function testAddRelationType()
    {
        $resource = new Ressource();
        $relationType = new RelationType();
        $resource->addRelationType($relationType);
        $this->assertInstanceOf(RelationType::class, $resource->getRelationType()[0]);
    }

    public function testGetCreatedAt()
    {
        $resource = new Ressource();
        $date = new \DateTimeImmutable();
        $resource->setCreatedAt($date);
        $this->assertInstanceOf(\DateTimeImmutable::class, $resource->getCreatedAt());
    }

    public function testSetType()
    {
        $resourceType = new RessourceType();
        $resource = new Ressource();
        $resource->setType($resourceType);
        $this->assertInstanceOf(RessourceType::class, $resource->getType());
    }

    public function testSetCreator()
    {
        $user = new User();
        $resource = new Ressource();
        $resource->setCreator($user);
        $this->assertInstanceOf(User::class, $resource->getCreator());
    }

    public function testSetCategory()
    {
        $resource = new Ressource();
        $category = new Category();
        $resource->setCategory($category);
        $this->assertInstanceOf(Category::class, $resource->getCategory());
    }

    public function testAddComment()
    {
        $comment = new Comment();
        $resource = new Ressource();
        $resource->addComment($comment);
        $this->assertInstanceOf(Comment::class, $resource->getComments()[0]);
    }

    public function testGetTitle()
    {
        $resource = new Ressource();
        $resource->setTitle('test_title');
        $this->assertEquals('test_title', $resource->getTitle());
        $this->assertIsString($resource->getTitle());
    }

    public function testRemoveComment()
    {
        $comment = new Comment();
        $resource = new Ressource();
        $resource->addComment($comment);
        $resource->removeComment($comment);
        $this->assertEmpty($resource->getComments());
    }

    public function testRemoveRelationType()
    {
        $resource = new Ressource();
        $relationType = new RelationType();
        $resource->addRelationType($relationType);
        $resource->removeRelationType($relationType);
        $this->assertEmpty($resource->getRelationType());
    }

    public function testIsIsPublished()
    {
        $resource = new Ressource();
        $resource->setIsPublished(true);
        $this->assertTrue($resource->isIsPublished());
        $this->assertIsBool($resource->isIsPublished());
    }

    public function testRemoveMedia()
    {
        $media = new Media();
        $resource = new Ressource();
        $resource->addMedia($media);
        $resource->removeMedia($media);
        $this->assertEmpty($resource->getMedia());
    }

    public function testGetComments()
    {
        $comment = new Comment();
        $resource = new Ressource();
        $resource->addComment($comment);
        $this->assertInstanceOf(Comment::class, $resource->getComments()[0]);
    }

    public function testGetExploitedRessources()
    {
        $exploited = new ExploitedRessource();
        $resource = new Ressource();
        $resource->addExploitedRessource($exploited);
        $this->assertInstanceOf(ExploitedRessource::class, $resource->getExploitedRessources()[0]);
    }

    public function test__construct()
    {
        $resource = new Ressource();
        $this->assertInstanceOf(Ressource::class, $resource);
    }

    public function testAddMedia()
    {
        $media = new Media();
        $resource = new Ressource();
        $resource->addMedia($media);
        $this->assertInstanceOf(Media::class, $resource->getMedia()[0]);
    }

    public function testAddFavorite()
    {
        $favorite = new Favorite();
        $resource = new Ressource();
        $resource->addFavorite($favorite);
        $this->assertInstanceOf(Favorite::class, $resource->getFavorites()[0]);
    }

    public function testGetDescription()
    {
        $resource = new Ressource();
        $resource->setDescription('test_description');
        $this->assertEquals('test_description', $resource->getDescription());
        $this->assertIsString($resource->getDescription());
    }

    public function testSetIsValid()
    {
        $resource = new Ressource();
        $resource->setIsValid(true);
        $this->assertTrue($resource->isIsValid());
        $this->assertIsBool($resource->isIsValid());
    }

    public function testGetIsFavorite()
    {
        $favorite = new Favorite();
        $resource = new Ressource();
        $resource->addFavorite($favorite);
        $this->assertInstanceOf(Favorite::class, $resource->getFavorites()[0]);
    }

    public function testSetCreatedAt()
    {
        $resource = new Ressource();
        $date = new \DateTimeImmutable();
        $resource->setCreatedAt($date);
        $this->assertInstanceOf(\DateTimeImmutable::class, $resource->getCreatedAt());
    }

    public function testGetNotifications()
    {
        $notification = new Notification();
        $resource = new Ressource();
        $resource->addNotification($notification);
        $this->assertInstanceOf(Notification::class, $resource->getNotifications()[0]);
    }

    public function testRemoveNotification()
    {
        $notification = new Notification();
        $resource = new Ressource();
        $resource->addNotification($notification);
        $resource->removeNotification($notification);
        $this->assertEmpty($resource->getNotifications());
    }

    public function testSetId()
    {
        $resource = new Ressource();
        $resource->setId(1);
        $this->assertEquals(1, $resource->getId());
        $this->assertIsInt($resource->getId());
    }

    public function testAddNotification()
    {
        $notification = new Notification();
        $resource = new Ressource();
        $resource->addNotification($notification);
        $this->assertInstanceOf(Notification::class, $resource->getNotifications()[0]);
    }

    public function testSetIsLiked()
    {
        $resource = new Ressource();
        $resource->setIsLiked(true);
        $this->assertTrue($resource->getIsLiked());
        $this->assertIsBool($resource->getIsLiked());
    }

    public function testGetId()
    {
        $resource = new Ressource();
        $resource->setId(1);
        $this->assertEquals(1, $resource->getId());
        $this->assertIsInt($resource->getId());
    }
}
