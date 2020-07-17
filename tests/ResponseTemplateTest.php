<?php
//declare(strict_types=1);

namespace HEXONETTEST;

use \HEXONET\ResponseTemplate as RT;

final class ResponseTemplateTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructorEmptyRaw(): void
    {
        $rt = new RT('');
        $this->assertEquals(423, $rt->getCode());
        $this->assertEquals("Empty API response. Probably unreachable API end point {CONNECTION_URL}", $rt->getDescription());
    }

    public function testInvalidAPIResponse(): void
    {
        $rt = new RT("[RESPONSE]\r\ncode=200\r\nqueuetime=0\r\nEOF\r\n");
        $this->assertEquals(423, $rt->getCode());
        $this->assertEquals("Invalid API response. Contact Support", $rt->getDescription());
    }
    
    public function testGetHash(): void
    {
        $h = (new RT(''))->getHash();
        $this->assertEquals("423", $h["CODE"]);
        $this->assertEquals("Empty API response. Probably unreachable API end point {CONNECTION_URL}", $h["DESCRIPTION"]);
    }

    public function testGetQueuetimeNo(): void
    {
        $rt = new RT('');
        $this->assertEquals(0, $rt->getQueuetime());
    }

    public function testGetQueuetime(): void
    {
        $rt = new RT("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response. Probably unreachable API end point\r\nqueuetime=0\r\nEOF\r\n");
        $this->assertEquals(0, $rt->getQueuetime());
    }

    public function testGetRuntimeNo(): void
    {
        $rt = new RT('');
        $this->assertEquals(0, $rt->getRuntime());
    }

    public function testGetRuntime(): void
    {
        $rt = new RT("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response. Probably unreachable API end point\r\nruntime=0.12\r\nEOF\r\n");
        $this->assertEquals(0.12, $rt->getRuntime());
    }

    public function testIsPendingNo(): void
    {
        $rt = new RT('');
        $this->assertEquals(false, $rt->isPending());
    }

    public function testIsPending(): void
    {
        $rt = new RT("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response. Probably unreachable API end point\r\npending=1\r\nEOF\r\n");
        $this->assertEquals(true, $rt->isPending());
    }
}
