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
}
