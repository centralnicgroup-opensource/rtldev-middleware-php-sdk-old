<?php

//declare(strict_types=1);

namespace HEXONETTEST;

use HEXONET\Response as R;
use HEXONET\ResponseTemplateManager as RTM;

final class ResponseTemplateManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testGetTemplateNotFound(): void
    {
        $tpl = RTM::getTemplate('IwontExist');
        $this->assertEquals(500, $tpl->getCode());
        $this->assertEquals('Response Template not found', $tpl->getDescription());
    }

    public function testGetTemplates(): void
    {
        $tpl = RTM::getTemplates();
        $keys = array_keys(RTM::$templates);
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $tpl);
        }
    }

    public function testIsTemplateMatchHash(): void
    {
        $tpl = new R('');
        $this->assertEquals(true, RTM::isTemplateMatchHash($tpl->getHash(), "empty"));
    }

    public function testIsTemplateMatchPlain(): void
    {
        $tpl = new R('');
        $this->assertEquals(true, RTM::isTemplateMatchPlain($tpl->getPlain(), "empty"));
    }

    public function testAddTemplate(): void
    {
        // providing template in plain text
        $tplid = "custom404";
        $descr = "Page not found";
        $code = 421;

        RTM::addTemplate($tplid, "[RESPONSE]\r\nCODE=$code\r\nDESCRIPTION=$descr\r\nEOF\r\n");
        $this->assertEquals(true, RTM::hasTemplate($tplid));
        $tpl = RTM::getTemplate($tplid);
        $this->assertEquals($code, $tpl->getCode());
        $this->assertEquals($descr, $tpl->getDescription());
        // providing template by code and description
        $tplid = "custom2_404";
        RTM::addTemplate($tplid, "". $code, $descr);
        $this->assertEquals(true, RTM::hasTemplate($tplid));
        $tpl = RTM::getTemplate($tplid);
        $this->assertEquals($code, $tpl->getCode());
        $this->assertEquals($descr, $tpl->getDescription());
    }
}
