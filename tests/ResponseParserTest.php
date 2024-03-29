<?php

//declare(strict_types=1);

namespace HEXONETTEST;

use HEXONET\ResponseParser as RP;
use HEXONET\ResponseTemplateManager as RTM;

final class ResponseParserTest extends \PHPUnit\Framework\TestCase
{

    public static function setupBeforeClass(): void
    {
        RTM::addTemplate("OK", "200", "Command completed successfully");
    }

    public function testSerializeProperty(): void
    {
        $r = RTM::getTemplate("OK")->getHash();
        $r["PROPERTY"] = [
          "DOMAIN" => ["mydomain1.com", "mydomain2.com", "mydomain3.com"],
          "RATING" => ["1", "2", "3"],
          "SUM" => [3]
        ];
        $this->assertEquals("[RESPONSE]\r\nPROPERTY[DOMAIN][0]=mydomain1.com\r\nPROPERTY[DOMAIN][1]=mydomain2.com\r\nPROPERTY[DOMAIN][2]=mydomain3.com\r\nPROPERTY[RATING][0]=1\r\nPROPERTY[RATING][1]=2\r\nPROPERTY[RATING][2]=3\r\nPROPERTY[SUM][0]=3\r\nCODE=200\r\nDESCRIPTION=Command completed successfully\r\nEOF\r\n", RP::serialize($r));
    }

    public function testSerializeNoProperty(): void
    {
        $tpl = RTM::getTemplate("OK");
        $this->assertEquals($tpl->getPlain(), RP::serialize($tpl->getHash()));
    }

    public function testSerializeNoCodeNoDescription(): void
    {
        $h = RTM::getTemplate("OK")->getHash();
        unset($h["CODE"]);
        unset($h["DESCRIPTION"]);
        $this->assertEquals("[RESPONSE]\r\nEOF\r\n", RP::serialize($h));
    }

    public function testSerializeQTandRT(): void
    {
        $h = RTM::getTemplate("OK")->getHash();
        $h["QUEUETIME"] = "0";
        $h["RUNTIME"] = "0.12";
        $this->assertEquals("[RESPONSE]\r\nCODE=200\r\nDESCRIPTION=Command completed successfully\r\nQUEUETIME=0\r\nRUNTIME=0.12\r\nEOF\r\n", RP::serialize($h));
    }
}
