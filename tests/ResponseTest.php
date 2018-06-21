<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    public function testResponseFormat() : void
    {
        $msg = "testResponseFormat: assertion failed";
        $api =  HEXONET\Connection::connect(array(
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

    public function testResponseFormatFurtherCoverage() : void
    {
        $api =  HEXONET\Connection::connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));      
        $r = $api->call(array("COMMAND" => "GetUserIndex"));
        $str = $r->asString();
        $this->assertInternalType("string", $str);

        $props = $r->properties();
        $this->assertInternalType("array", $props);
        $this->assertArrayHasKey('USERINDEX', $props);
        $this->assertArrayHasKey('PARENTUSERINDEX', $props);
        $this->assertCount(1, $props['USERINDEX']);
        $this->assertCount(1, $props['PARENTUSERINDEX']);
        $this->assertEquals("659", $props['USERINDEX'][0]);
        $this->assertEquals("199", $props['PARENTUSERINDEX'][0]);

        $this->assertEquals(true, $r->isSuccess());
        $this->assertEquals(false, $r->isTmpError());

        $this->assertEquals(null, $r->__get("idontexist"));

        $r->offsetSet(0, "value");//NOT implemented BUT existing
        $r->offsetUnset(0);//NOT implemented BUT existing

        $this->assertEquals(true, $r->offsetExists(0));
        $this->assertEquals(true, $r->offsetExists("CODE"));

        $row = $r->offsetGet(0);
        $this->assertInternalType("array", $row);
        $this->assertArrayHasKey('USERINDEX', $row);
        $this->assertArrayHasKey('PARENTUSERINDEX', $row);
        $this->assertEquals("659", $row['USERINDEX']);
        $this->assertEquals("199", $row['PARENTUSERINDEX']);

        $this->assertEquals("200", $r->offsetGet("CODE"));

        $cols = $r->columns();
        $this->assertInternalType("array", $cols);
        $this->assertCount(2, $cols);
        $this->assertContainsOnly("string", $cols);
        $this->assertContains("USERINDEX", $cols);
        $this->assertContains("PARENTUSERINDEX", $cols);

        $this->assertEquals(0, $r->first());
        // I would not expect the following to be correct
        // ----- we might review this ------------------------
        $this->assertEquals(-1, $r->last());//expecting 1 here
        $this->assertEquals(0, $r->count());//expecting 1 here
        $this->assertEquals(0, $r->total());//expecting 1 here
        $this->assertEquals(0, $r->pages());//expecting 1 here
        $this->assertEquals(-1, $r->lastpagefirst());//-1 ???
        // ---------------------------------------------------
        $this->assertEquals(1, $r->limit());
        $this->assertEquals(1, $r->page());
        $this->assertEquals(null, $r->prevpage());
        $this->assertEquals(null, $r->nextpage());
        $this->assertEquals(null, $r->prevpagefirst());
        $this->assertEquals(null, $r->nextpagefirst());        
        
        $row = $r->rewind();
        $this->assertInternalType("array", $row);
        $this->assertArrayHasKey('USERINDEX', $row);
        $this->assertArrayHasKey('PARENTUSERINDEX', $row);
        $this->assertEquals("659", $row['USERINDEX']);
        $this->assertEquals("199", $row['PARENTUSERINDEX']);

        $row2 = $r->current();
        $this->assertEquals($row, $row2);

        $this->assertEquals(0, $r->key());
        $r->next();
        $this->assertEquals(null, $r->current());
        $this->assertEquals(null, $r->valid());
    }
}