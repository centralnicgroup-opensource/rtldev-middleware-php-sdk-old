<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    public function testResponseFormat() : void
    {
        $msg = "testResponseFormat: assertion failed";
        $api = HEXONET\connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));      
        $r = $api->call(array("COMMAND" => "GetUserIndex"));
        
        $this->assertEquals(200, $r->code(), $msg);
        $this->assertEquals("Command completed successfully", $r->description(), $msg);
        $this->assertEquals("Command completed successfully", $r->description(), $msg);
        $this->assertGreaterThanOrEqual(0, $r->runtime(), $msg);
        $this->assertGreaterThanOrEqual(0, $r->queuetime(), $msg);
        $tmp = $r->property("some non int arg");
        $this->assertInternalType("array", $tmp, $msg);
        $this->assertCount(1, $tmp, $msg);
        $this->assertCount(2, $tmp[0], $msg);
        $this->assertArrayHasKey('USERINDEX', $tmp[0], $msg);
        $this->assertArrayHasKey('PARENTUSERINDEX', $tmp[0], $msg);
        $this->assertEquals("659", $tmp[0]['USERINDEX'], $msg);
        $this->assertEquals("199", $tmp[0]['PARENTUSERINDEX'], $msg);
        $tmp = $r->property(0);
        $this->assertInternalType("array", $tmp, $msg);
        $this->assertCount(2, $tmp, $msg);
        $this->assertArrayHasKey('USERINDEX', $tmp, $msg);
        $this->assertArrayHasKey('PARENTUSERINDEX', $tmp, $msg);
        $this->assertEquals("659", $tmp['USERINDEX'], $msg);
        $this->assertEquals("199", $tmp['PARENTUSERINDEX'], $msg);
    }
}