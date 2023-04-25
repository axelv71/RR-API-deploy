<?php

namespace App\Tests\Unit;

use App\Entity\Language;
use App\Entity\Settings;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{

    public function testCreate()
    {
        $language = Language::create('name', 'label');
        $this->assertInstanceOf(Language::class, $language);
    }

    public function testGetLabel()
    {
        $language = Language::create('label', 'name');
        $this->assertEquals('label', $language->getLabel());
        $this->assertIsString($language->getLabel());
    }

    public function test__construct()
    {
        $language = new Language();
        $this->assertInstanceOf(Language::class, $language);
    }

    public function testAddSetting()
    {
        $setting = new Settings();
        $language = new Language();
        $language->addSetting($setting);
        $this->assertInstanceOf(Settings::class, $language->getSettings()[0]);
    }

    public function testSetName()
    {
        $language = new Language();
        $language->setName('test_name');
        $this->assertEquals('test_name', $language->getName());
        $this->assertIsString($language->getName());
    }

    public function testRemoveSetting()
    {
        $setting = new Settings();
        $language = new Language();
        $language->addSetting($setting);
        $language->removeSetting($setting);
        $this->assertEmpty($language->getSettings());
    }

    public function testGetSettings()
    {
        $setting = new Settings();
        $language = new Language();
        $language->addSetting($setting);
        $this->assertInstanceOf(Settings::class, $language->getSettings()[0]);
    }

    public function testSetLabel()
    {
        $language = new Language();
        $language->setLabel('test_label');
        $this->assertEquals('test_label', $language->getLabel());
        $this->assertIsString($language->getLabel());
    }

    public function testGetName()
    {
        $language = new Language();
        $language->setName('test_name');
        $this->assertEquals('test_name', $language->getName());
        $this->assertIsString($language->getName());
    }
}
