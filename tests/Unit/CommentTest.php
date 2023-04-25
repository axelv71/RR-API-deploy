<?php


namespace App\Tests\Unit;

use App\Entity\Comment;
use App\Entity\Ressource;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{

    public function testGetContent()
    {
        $comment = new Comment();
        $comment->setContent('test_content');
        $this->assertEquals('test_content', $comment->getContent());
        $this->assertIsString($comment->getContent());
    }

    public function testSetId()
    {
        $comment = new Comment();
        $comment->setId(1);
        $this->assertEquals(1, $comment->getId());
        $this->assertIsInt($comment->getId());
    }

    public function testSetContent()
    {
        $comment = new Comment();
        $comment->setContent('test_content');
        $this->assertEquals('test_content', $comment->getContent());
        $this->assertIsString($comment->getContent());
    }

    public function testGetId()
    {
        $comment = new Comment();
        $comment->setId(1);
        $this->assertEquals(1, $comment->getId());
        $this->assertIsInt($comment->getId());
    }

    public function testGetRessource()
    {
        $comment = new Comment();
        $resource = new Ressource();
        $comment->setRessource($resource);
        $this->assertInstanceOf(Ressource::class, $comment->getRessource());
    }

    public function testGetCreator()
    {
        $comment = new Comment();
        $creator = new User();
        $comment->setCreator($creator);
        $this->assertInstanceOf(User::class, $comment->getCreator());
    }

    public function testSetIsValid()
    {
        $comment = new Comment();
        $comment->setIsValid(true);
        $this->assertTrue($comment->isIsValid());
        $this->assertIsBool($comment->isIsValid());
    }

    public function test__construct()
    {
        $comment = new Comment();
        $this->assertInstanceOf(Comment::class, $comment);
    }

    public function testIsIsValid()
    {
        $comment = new Comment();
        $comment->setIsValid(true);
        $this->assertTrue($comment->isIsValid());
        $this->assertIsBool($comment->isIsValid());
    }

    public function testSetCreateAt()
    {
        $comment = new Comment();
        $comment->setCreateAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $comment->getCreateAt());
    }

    public function testSetCreator()
    {
        $comment = new Comment();
        $creator = new User();
        $comment->setCreator($creator);
        $this->assertInstanceOf(User::class, $comment->getCreator());
    }

    public function testGetCreateAt()
    {
        $comment = new Comment();
        $comment->setCreateAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $comment->getCreateAt());
    }

    public function testSetRessource()
    {
        $comment = new Comment();
        $resource = new Ressource();
        $comment->setRessource($resource);
        $this->assertInstanceOf(Ressource::class, $comment->getRessource());
    }
}
