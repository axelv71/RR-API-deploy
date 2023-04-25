<?php

namespace App\Tests\Unit;
use App\Entity\Language;
use App\Entity\Settings;
use App\Entity\Theme;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{

    public function testGetUser()
    {
        $user = new User();
        $settings = new Settings();
        $settings->setUser($user);
        $this->assertInstanceOf(User::class, $settings->getUser());
    }

    public function testSetUser()
    {
        $user = new User();
        $settings = new Settings();
        $settings->setUser($user);
        $this->assertInstanceOf(User::class, $settings->getUser());
    }

    public function testIsIsDark()
    {
        $setting = new Settings();
        $setting->setIsDark(true);
        $this->assertTrue($setting->isIsDark());
        $this->assertIsBool($setting->isIsDark());
    }

    public function testIsAllowNotifications()
    {
        $setting = new Settings();
        $setting->setAllowNotifications(true);
        $this->assertTrue($setting->isAllowNotifications());
        $this->assertIsBool($setting->isAllowNotifications());
    }

    public function testGetCreatedAt()
    {
        $setting = new Settings();
        $this->assertInstanceOf(\DateTimeImmutable::class, $setting->getCreatedAt());
    }

    public function testSetUseDeviceMode()
    {
        $setting = new Settings();
        $setting->setUseDeviceMode(true);
        $this->assertTrue($setting->isUseDeviceMode());
        $this->assertIsBool($setting->isUseDeviceMode());
    }

    public function testGetTheme()
    {
        $setting = new Settings();
        $theme = new Theme();
        $setting->setTheme($theme);
        $this->assertInstanceOf(Theme::class, $setting->getTheme());
    }

    public function testCreate()
    {
        $isDark = true;
        $allowNotifications = true;
        $useDeviceMode = true;
        $language = new Language();
        $theme = new Theme();

        $setting = Settings::create($isDark, $allowNotifications, $useDeviceMode, $language, $theme);
        $this->assertInstanceOf(Settings::class, $setting);
    }

    public function testIsUseDeviceMode()
    {
        $setting = new Settings();
        $setting->setUseDeviceMode(true);
        $this->assertTrue($setting->isUseDeviceMode());
        $this->assertIsBool($setting->isUseDeviceMode());
    }

    public function testSetIsDark()
    {
        $setting = new Settings();
        $setting->setIsDark(true);
        $this->assertTrue($setting->isIsDark());
        $this->assertIsBool($setting->isIsDark());
    }

    public function testGetLanguage()
    {
        $setting = new Settings();
        $language = new Language();
        $setting->setLanguage($language);
        $this->assertInstanceOf(Language::class, $setting->getLanguage());
    }

    public function test__construct()
    {
        $setting = new Settings();
        $this->assertInstanceOf(Settings::class, $setting);
    }

    public function testSetTheme()
    {
        $theme = new Theme();
        $setting = new Settings();
        $setting->setTheme($theme);
        $this->assertInstanceOf(Theme::class, $setting->getTheme());
    }

    public function testSetLanguage()
    {
        $language = new Language();
        $setting = new Settings();
        $setting->setLanguage($language);
        $this->assertInstanceOf(Language::class, $setting->getLanguage());
    }

    public function testSetAllowNotifications()
    {
        $setting = new Settings();
        $setting->setAllowNotifications(true);
        $this->assertTrue($setting->isAllowNotifications());
        $this->assertIsBool($setting->isAllowNotifications());
    }

    public function testSetCreatedAt()
    {
        $setting = new Settings();
        $this->assertInstanceOf(\DateTimeImmutable::class, $setting->getCreatedAt());
    }
}
