<?php
declare(strict_types=1);

namespace HEXONETTEST;

use PHPUnit\Framework\TestCase;
use HEXONET\ResponseTemplate as RT;

final class ResponseTemplateTest extends TestCase
{
    public function testConstructorEmptyRaw()
    {
        $rt = new RT('');
        $this->assertEquals(423, $rt->getCode());
        $this->assertEquals("Empty API response", $rt->getDescription());
    }
    
    public function testGetHash()
    {
        $h = (new RT(''))->getHash();
        $this->assertEquals("423", $h["CODE"]);
        $this->assertEquals("Empty API response", $h["DESCRIPTION"]);
    }

    public function testGetQueuetimeNo()
    {
        $rt = new RT('');
        $this->assertEquals(0, $rt->getQueuetime());
    }

    public function testGetQueuetime()
    {
        $rt = new RT("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response\r\nqueuetime=0\r\nEOF\r\n");
        $this->assertEquals(0, $rt->getQueuetime());
    }

    public function testGetRuntimeNo()
    {
        $rt = new RT('');
        $this->assertEquals(0, $rt->getRuntime());
    }

    public function testGetRuntime()
    {
        $rt = new RT("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response\r\nruntime=0.12\r\nEOF\r\n");
        $this->assertEquals(0.12, $rt->getRuntime());
    }
}
