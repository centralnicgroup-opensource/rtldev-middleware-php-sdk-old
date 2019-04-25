<?php
declare(strict_types=1);

namespace HEXONETTEST;

use PHPUnit\Framework\TestCase;
use HEXONET\ResponseTemplate as RT;
use HEXONET\ResponseTemplateManager as RTM;

final class ResponseTemplateManagerTest extends TestCase
{
    public static $rtm;

    public static function setupBeforeClass(): void
    {
        self::$rtm = RTM::getInstance();
    }

    public static function tearDownAfterClass(): void
    {
        self::$rtm = null;
    }

    public function testUniqueness()
    {
        $firstCall = RTM::getInstance();
        $secondCall = RTM::getInstance();
        $this->assertInstanceOf(RTM::class, $firstCall);
        $this->assertSame($firstCall, $secondCall);
    }

    public function testClone()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage("Call to private HEXONET\ResponseTemplateManager::__clone() from context 'HEXONETTEST\ResponseTemplateManagerTest'");
        $rtm = clone self::$rtm;
    }

    public function testGetTemplateNotFound()
    {
        $tpl = self::$rtm->getTemplate('IwontExist');
        $this->assertEquals(500, $tpl->getCode());
        $this->assertEquals('Response Template not found', $tpl->getDescription());
    }

    public function testGetTemplates()
    {
        $tpl = self::$rtm->getTemplates();
        $this->assertArrayHasKey("404", $tpl);
        $this->assertArrayHasKey("500", $tpl);
        $this->assertArrayHasKey("error", $tpl);
        $this->assertArrayHasKey("httperror", $tpl);
        $this->assertArrayHasKey("empty", $tpl);
        $this->assertArrayHasKey("unauthorized", $tpl);
        $this->assertArrayHasKey("expired", $tpl);
        $this->assertArrayHasKey("nocurl", $tpl);
    }

    public function testIsTemplateMatchHash()
    {
        $tpl = new RT('');
        $this->assertEquals(true, self::$rtm->isTemplateMatchHash($tpl->getHash(), "empty"));
    }

    public function testIsTemplateMatchPlain()
    {
        $tpl = new RT('');
        $this->assertEquals(true, self::$rtm->isTemplateMatchPlain($tpl->getPlain(), "empty"));
    }

    public function testAddTemplate()
    {
        $tpl = self::$rtm->addTemplate('test', '')->getTemplate('test');
        $this->assertEquals(true, self::$rtm->isTemplateMatchPlain($tpl->getPlain(), "empty"));
    }
}
