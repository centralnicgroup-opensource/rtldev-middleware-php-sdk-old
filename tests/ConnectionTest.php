<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ConnectionTest extends TestCase
{
    public function testGlobalConnectMethodThrows() : void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Credentials missing');
        $api =  HEXONET\Connection::connect();
    }

    public function testGlobalConnectMethodNoUser() : void
    {
        $api = HEXONET\Connection::connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array("COMMAND" => "GetUserIndex"));
        $this->assertInstanceOf(HEXONET\Response::class, $r);
    }

    public function testGlobalConnectMethodUser() : void
    {
        $api =  HEXONET\Connection::connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd",
            "user" => "hexotestman.com"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array("COMMAND" => "GetUserIndex"));
        $this->assertInstanceOf(HEXONET\Response::class, $r);
    }


    public function testGlobalConnectMethodRole() : void
    {
        $api = HEXONET\Connection::connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd",
            "role" => "test"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array("COMMAND" => "GetUserIndex"));
        $this->assertInstanceOf(HEXONET\Response::class, $r);
    }

    public function testCallConfigParameter() : void
    {
        $api =  HEXONET\Connection::connect(array(
            "url" => "https://coreapi.1api.net/api/call.cgi",
            "entity" => "1234",
            "login" => "test.user",
            "password" => "test.passw0rd"
        ));
        $this->assertInstanceOf(HEXONET\Connection::class, $api);
        
        $r = $api->call(array("COMMAND" => "GetUserIndex"), array("user"=>"hexotestman.com"));
        $this->assertInstanceOf(HEXONET\Response::class, $r);
    }
}