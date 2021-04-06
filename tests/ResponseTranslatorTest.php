<?php

//declare(strict_types=1);

namespace HEXONETTEST;

use HEXONET\Response as R;
use HEXONET\ResponseTranslator as RT;
use HEXONET\ResponseTemplateManager as RTM;

final class ResponseTranslatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test place holder vars replacement mechanism
     */
    public function testPlaceHolderReplacements(): void
    {
        $cmd = ["COMMAND" => "StatusAccount"];

        // ensure no vars are returned in response, just in case no place holder replacements are provided
        $r = new R("");
        $this->assertEquals(0, preg_match("/\{[A-Z_]+\}/", $r->getDescription()), "case 1");

        // ensure variable replacements are correctly handled in case place holder replacements are provided
        $r = new R("", ["COMMAND" => "StatusAccount"], ["CONNECTION_URL" => "123HXPHFOUND123"]);
        $this->assertEquals(true, preg_match("/123HXPHFOUND123/", $r->getDescription()), "case 2");
    }

    /**
     * Test isTemplateMatchHash method
     */
    public function testIsTemplateMatchHash(): void
    {
        $cmd = ["COMMAND" => "StatusAccount"];
        $r = new R("");
        $this->assertTrue(RTM::isTemplateMatchHash($r->getHash(), "empty"));
    }

    /**
     * Test isTemplateMatchPlain method
     */
    public function testIsTemplateMatchPlain(): void
    {
        $cmd = ["COMMAND" => "StatusAccount"];
        $r = new R("");
        $this->assertTrue(RTM::isTemplateMatchPlain($r->getPlain(), "empty"));
    }

    /**
     * Test constructor
     */
    public function testConstructorVars(): void
    {
        $cmd = ["COMMAND" => "StatusAccount"];
        $r = new R("");
        $this->assertEquals(423, $r->getCode());
        $this->assertEquals("Empty API response. Probably unreachable API end point", $r->getDescription());
    }

    /**
     * Test constructor with invalid API response
     */
    public function testInvalidResponse(): void
    {
        $cmd = ["COMMAND" => "StatusAccount"];
        $raw = RT::translate("[RESPONSE]\r\ncode=200\r\nqueuetime=0\r\nEOF\r\n", $cmd);

        $r = new R($raw);
        $this->assertEquals(423, $r->getCode());
        $this->assertEquals("Invalid API response. Contact Support", $r->getDescription());
    }

    /**
     * Test getHash method
     */
    public function testGetHash(): void
    {
        $cmd = ["COMMAND" => "StatusAccount"];
        $r = new R("");
        $h = $r->getHash();
        $this->assertEquals("423", $h["CODE"]);
        $this->assertEquals("Empty API response. Probably unreachable API end point", $h["DESCRIPTION"]);
    }
}
