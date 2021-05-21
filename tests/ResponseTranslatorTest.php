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

    /**
     * Test ACL error translation
     */
    public function testACLTranslation(): void
    {
        $cmd = ["COMMAND" => "StatusAccount"];
        $r = new R("[RESPONSE]\r\ncode=530\r\ndescription=Authorization failed; Operation forbidden by ACL\r\nEOF\r\n", $cmd);
        $this->assertEquals(530, $r->getCode());
        $this->assertEquals("Authorization failed; Used Command `StatusAccount` not white-listed by your Access Control List", $r->getDescription());
    }

    /**
     * Test CheckDomainTransfer translations
     */
    public function testCheckDomainTransferTranslation(): void
    {
        $cmd = [
            "COMMAND" => "CheckDomainTransfer",
            "DOMAIN" => "mydomain.com",
            "AUTH" => "blablabla"
        ];

        // status: locked
        $r = new R("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY STATUS (clientTransferProhibited)\r\nEOF\r\n", $cmd);
        $this->assertEquals(219, $r->getCode());
        $this->assertEquals("This Domain is locked. Initiating a Transfer is therefore impossible.", $r->getDescription());

        // status: requested
        $r = new R("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY STATUS (requested)\r\nEOF\r\n", $cmd);
        $this->assertEquals(219, $r->getCode());
        $this->assertEquals("Registration of this Domain Name has not yet completed. Initiating a Transfer is therefore impossible.", $r->getDescription());

        // status: requestedcreate
        $r = new R("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY STATUS (requestedcreate)\r\nEOF\r\n", $cmd);
        $this->assertEquals(219, $r->getCode());
        $this->assertEquals("Registration of this Domain Name has not yet completed. Initiating a Transfer is therefore impossible.", $r->getDescription());

        // status: requesteddelete
        $r = new R("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY STATUS (requesteddelete)\r\nEOF\r\n", $cmd);
        $this->assertEquals(219, $r->getCode());
        $this->assertEquals("Deletion of this Domain Name has been requested. Initiating a Transfer is therefore impossible.", $r->getDescription());

        // status: pendingdelete
        $r = new R("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY STATUS (pendingdelete)\r\nEOF\r\n", $cmd);
        $this->assertEquals(219, $r->getCode());
        $this->assertEquals("Deletion of this Domain Name is pending. Initiating a Transfer is therefore impossible.", $r->getDescription());

        // Wrong AUTH
        $r = new R("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY WRONG AUTH\r\nEOF\r\n", $cmd);
        $this->assertEquals(219, $r->getCode());
        $this->assertEquals("The given Authorization Code is wrong. Initiating a Transfer is therefore impossible.", $r->getDescription());

        // mixed status `locked` and Wrong Auth
        $r = new R("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY STATUS (clientTransferProhibited)/WRONG AUTH\r\nEOF\r\n", $cmd);
        $this->assertEquals(219, $r->getCode());
        $this->assertEquals("This Domain is locked and the given Authorization Code is wrong. Initiating a Transfer is therefore impossible.", $r->getDescription());

        // Age of the Domain <= 60d
        $r = new R("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY AGE OF THE DOMAIN\r\nEOF\r\n", $cmd);
        $this->assertEquals(219, $r->getCode());
        $this->assertEquals("This Domain Name is within 60 days of initial registration. Initiating a Transfer is therefore impossible.", $r->getDescription());
    }

    /**
     * Test translate fn
     */
    public function testTranslate(): void
    {
        $cmd = [
            "COMMAND" => "CheckDomainTransfer",
            "DOMAIN" => "mydomain.com",
            "AUTH" => "blablabla"
        ];
        // no placeholder vars provided
        $r = RT::translate("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY STATUS (clientTransferProhibited)\r\nEOF\r\n", $cmd);
        $this->assertEquals("[RESPONSE]\r\ncode=219\r\ndescription=This Domain is locked. Initiating a Transfer is therefore impossible.\r\nEOF\r\n", $r);
        // placeholder vars provided
        $r = RT::translate("[RESPONSE]\r\ncode=219\r\ndescription=Request is not available; DOMAIN TRANSFER IS PROHIBITED BY STATUS (clientTransferProhibited)\r\nEOF\r\n", $cmd, []);
        $this->assertEquals("[RESPONSE]\r\ncode=219\r\ndescription=This Domain is locked. Initiating a Transfer is therefore impossible.\r\nEOF\r\n", $r);
        // template match
        $r = RT::translate("[RESPONSE]\r\ncode=219\r\nEOF\r\n", $cmd);
        $this->assertEquals("[RESPONSE]\r\nCODE=423\r\nDESCRIPTION=Invalid API response. Contact Support\r\nEOF\r\n", $r);
    }
}
