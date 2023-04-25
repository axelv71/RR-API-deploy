<?php

namespace App\Tests\Unit;

use App\Entity\Media;
use App\Entity\Ressource;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class MediaTest extends TestCase
{

    public function testSetFileSize()
    {
        $media = new Media();
        $media->setFileSize(1);
        $this->assertEquals(1, $media->getFileSize());
    }

    public function test__construct()
    {
        $media = new Media();
        $this->assertInstanceOf(Media::class, $media);
    }

    public function testSetFile()
    {
        $file = new File('./test.png');
        $media = new Media();
        $media->setFile($file);
        $this->assertInstanceOf(File::class, $media->getFile());
    }

    public function testGetFilePath()
    {
        $file = new File('./test.png');
        $media = new Media();
        $media->setFile($file);
        $media->setFilePath('./test.png');
        $this->assertEquals('./test.png', $media->getFilePath());
        $this->assertIsString($media->getFilePath());
    }

    public function testSetCreatedAt()
    {
        $media = new Media();
        $media->setCreatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $media->getCreatedAt());
    }

    public function testGetUpdatedAt()
    {
        $media = new Media();
        $this->assertInstanceOf(\DateTimeImmutable::class, $media->getUpdatedAt());
    }

    public function testGetRessource()
    {
        $resource = new Ressource();
        $media = new Media();
        $media->setRessource($resource);
        $this->assertInstanceOf(Ressource::class, $media->getRessource());
    }

    public function testSetUpdatedAt()
    {
        $media = new Media();
        $media->setUpdatedAt(new \DateTimeImmutable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $media->getUpdatedAt());
    }

    public function testGetTitle()
    {
        $media = new Media();
        $media->setTitle('test_title');
        $this->assertEquals('test_title', $media->getTitle());
        $this->assertIsString($media->getTitle());
    }

    public function testGetMimetype()
    {
        $media = new Media();
        $media->setMimetype('test_mimetype');
        $this->assertEquals('test_mimetype', $media->getMimetype());
        $this->assertIsString($media->getMimetype());
    }

    public function testGetFile()
    {
        $file = new File('./test.png');
        $media = new Media();
        $media->setFile($file);
        $this->assertInstanceOf(File::class, $media->getFile());
    }

    public function testSetRessource()
    {
        $resource = new Ressource();
        $media = new Media();
        $media->setRessource($resource);
        $this->assertInstanceOf(Ressource::class, $media->getRessource());
    }

    public function testGetFileSize()
    {
        $media = new Media();
        $media->setFileSize(1);
        $this->assertEquals(1, $media->getFileSize());
    }

    public function testSetMimetype()
    {
        $media = new Media();
        $media->setMimetype('test_mimetype');
        $this->assertEquals('test_mimetype', $media->getMimetype());
        $this->assertIsString($media->getMimetype());
    }

    public function testGetCreatedAt()
    {
        $media = new Media();
        $this->assertInstanceOf(\DateTimeImmutable::class, $media->getCreatedAt());
    }

    public function testSetTitle()
    {
        $media = new Media();
        $media->setTitle('test_title');
        $this->assertEquals('test_title', $media->getTitle());
        $this->assertIsString($media->getTitle());
    }

    public function testSetFilePath()
    {
        $media = new Media();
        $media->setFilePath('./test.png');
        $this->assertEquals('./test.png', $media->getFilePath());
        $this->assertIsString($media->getFilePath());
    }
}
