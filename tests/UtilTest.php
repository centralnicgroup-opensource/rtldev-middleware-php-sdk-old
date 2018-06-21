<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UtilTest extends TestCase
{

    public function testCommandEncodeWithNestedCMDArray() : void
    {
        $api = HEXONET\connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array(
                "COMMAND" => "AddContact",
                "OWNERCONTACT"  => array(
                    "STREET" => "test street 123"
                )
            ),
            array(
                 "user"=>"hexotestman.com"
            )
        );
        $this->assertInstanceOf(HEXONET\Response::class, $r);
    }

    public function testCommandEncodeWithCMDArray() : void
    {
        $api = HEXONET\connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array(
                "COMMAND" => "CheckDomains",
                "DOMAIN"  => array(
                    "testdomain.com"
                )
            ),
            array(
                 "user"=>"hexotestman.com"
            )
        );
        $this->assertInstanceOf(HEXONET\Response::class, $r);
    }

    public function testResponseToHashFromListCMD() : void
    {
        $api = HEXONET\connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array(
            "COMMAND" => "QueryDomainList",
            "LIMIT" => 10
        ));
        $this->assertInstanceOf(HEXONET\Response::class, $r);
    }

    public function testResponseToListHashFromListCMD() : void
    {
        $api = HEXONET\connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array(
            "COMMAND" => "QueryDomainList",
            "FIRST" => 0,
            "LIMIT" => 10
        ));
        $this->assertInstanceOf(HEXONET\Response::class, $r);
        $list = $r->asList();
        $this->assertInternalType("array", $list);
        $this->assertCount(10, $list);
    }

    public function testResponseToListHashFromListCMDPage2() : void
    {
        $api = HEXONET\connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array(
            "COMMAND" => "QueryDomainList",
            "FIRST" => 10,
            "LIMIT" => 10
        ));
        $this->assertInstanceOf(HEXONET\Response::class, $r);
        $list = $r->asList();
        $this->assertInternalType("array", $list);
        $this->assertCount(10, $list);
    }

    public function testsqltime() : void
    {
        $sqltime = HEXONET\Util::sqltime(1529568630);
        $this->assertEquals("2018-06-21 08:10:30", $sqltime);

        $sqltime = HEXONET\Util::sqltime();
        $this->assertInternalType("string", $sqltime);
    }

    public function testtimesql() : void
    {
        $time = HEXONET\Util::timesql("2018-06-21 08:10:30");
        $this->assertEquals(1529568630, $time);
    }

    public function testurlencode() : void
    {
        $enc = HEXONET\Util::urlEncode("asdf&1234");
        $this->assertEquals("asdf%261234", $enc);
    }

    public function testurldecode() : void
    {
        $dec = HEXONET\Util::urlDecode("asdf%261234");
        $this->assertEquals("asdf&1234", $dec);
    }

    public function testbase64encode() : void
    {
        $enc = HEXONET\Util::base64Encode("iamnotencoded");
        $this->assertEquals("aWFtbm90ZW5jb2RlZA==", $enc);
    }

    public function testbase64decode() : void
    {
        $dec = HEXONET\Util::base64Decode("aWFtbm90ZW5jb2RlZA==");
        $this->assertEquals("iamnotencoded", $dec);
    }
}