<?php

namespace App\Tests\Unit;
use App\Entity\Settings;
use App\Entity\Theme;
use PHPUnit\Framework\TestCase;

class ThemeTest extends TestCase
{

    public function testSetLabel()
    {
        $theme = new Theme();
        $theme->setLabel('test_label');
        $this->assertEquals('test_label', $theme->getLabel());
        $this->assertIsString($theme->getLabel());
    }

    public function testSetPrimaryColor()
    {
        $theme = new Theme();
        $theme->setPrimaryColor('test_primaryColor');
        $this->assertEquals('test_primaryColor', $theme->getPrimaryColor());
        $this->assertIsString($theme->getPrimaryColor());
    }

    public function testRemoveSetting()
    {
        $theme = new Theme();
        $setting = new Settings();
        $theme->addSetting($setting);
        $theme->removeSetting($setting);
        $this->assertEmpty($theme->getSettings());
    }

    public function testCreate()
    {
        $theme = Theme::create('test_label', 'test_name', 'test_primaryColor', 'test_secondaryColor');
        $this->assertInstanceOf(Theme::class, $theme);
    }

    public function test__construct()
    {
        $theme = new Theme();
        $this->assertInstanceOf(Theme::class, $theme);
    }

    public function testAddSetting()
    {
        $theme = new Theme();
        $setting = new Settings();
        $theme->addSetting($setting);
        $this->assertInstanceOf(Settings::class, $theme->getSettings()[0]);
    }

    public function testGetName()
    {
        $theme = new Theme();
        $theme->setName('test_name');
        $this->assertEquals('test_name', $theme->getName());
        $this->assertIsString($theme->getName());
    }

    public function testGetSettings()
    {
        $theme = new Theme();
        $setting = new Settings();
        $theme->addSetting($setting);
        $this->assertInstanceOf(Settings::class, $theme->getSettings()[0]);
    }

    public function testSetName()
    {
        $theme = new Theme();
        $theme->setName('test_name');
        $this->assertEquals('test_name', $theme->getName());
        $this->assertIsString($theme->getName());
    }

    public function testGetPrimaryColor()
    {
        $theme = new Theme();
        $theme->setPrimaryColor('test_primaryColor');
        $this->assertEquals('test_primaryColor', $theme->getPrimaryColor());
        $this->assertIsString($theme->getPrimaryColor());
    }

    public function testSetSecondaryColor()
    {
        $theme = new Theme();
        $theme->setSecondaryColor('test_secondaryColor');
        $this->assertEquals('test_secondaryColor', $theme->getSecondaryColor());
        $this->assertIsString($theme->getSecondaryColor());
    }

    public function testGetLabel()
    {
        $theme = new Theme();
        $theme->setLabel('test_label');
        $this->assertEquals('test_label', $theme->getLabel());
        $this->assertIsString($theme->getLabel());
    }

    public function testGetSecondaryColor()
    {
        $theme = new Theme();
        $theme->setSecondaryColor('test_secondaryColor');
        $this->assertEquals('test_secondaryColor', $theme->getSecondaryColor());
        $this->assertIsString($theme->getSecondaryColor());
    }
}
