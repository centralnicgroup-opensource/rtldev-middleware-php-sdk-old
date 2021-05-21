<?php

//declare(strict_types=1);

namespace HEXONETTEST;

use HEXONET\Response as R;
use HEXONET\ResponseTemplateManager as RTM;
use HEXONET\ResponseParser as RP;

final class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public static function setupBeforeClass(): void
    {
        RTM::addTemplate("OK", "200", "Command completed successfully")
            ::addTemplate("listP0", "[RESPONSE]\r\nPROPERTY[TOTAL][0]=2701\r\nPROPERTY[FIRST][0]=0\r\nPROPERTY[DOMAIN][0]=0-60motorcycletimes.com\r\nPROPERTY[DOMAIN][1]=0-be-s01-0.com\r\nPROPERTY[COUNT][0]=2\r\nPROPERTY[LAST][0]=1\r\nPROPERTY[LIMIT][0]=2\r\nDESCRIPTION=Command completed successfully\r\nCODE=200\r\nQUEUETIME=0\r\nRUNTIME=0.023\r\nEOF\r\n");
    }

    public function testCommandPlain(): void
    {
        // ensure no vars are returned in response, just in case no place holder replacements are provided
        $r = new R("", ["COMMAND" => "QueryDomainOptions", "DOMAIN0" => "example.com", "DOMAIN1" => "example.net" ]);
        $expected = "COMMAND = QueryDomainOptions\nDOMAIN0 = example.com\nDOMAIN1 = example.net\n";
        $this->assertEquals($expected, $r->getCommandPlain());
    }

    public function testCommandPlainSecure(): void
    {
        // ensure no vars are returned in response, just in case no place holder replacements are provided
        $r = new R("", ["COMMAND" => "CheckAuthentication", "SUBUSER" => "test.user", "PASSWORD" => "test.passw0rd" ]);
        $expected = "COMMAND = CheckAuthentication\nSUBUSER = test.user\nPASSWORD = ***\n";
        $this->assertEquals($expected, $r->getCommandPlain());
    }

    public function testGetCurrentPageNumberEntries(): void
    {
        $r = new R("listP0");
        $this->assertEquals(1, $r->getCurrentPageNumber());
    }

    public function testGetCurrentPageNumberNoEntries(): void
    {
        $r = new R("OK");
        $this->assertNull($r->getCurrentPageNumber());
    }

    public function testGetFirstRecordIndexNoFirstNoRows(): void
    {
        $r = new R("OK");
        $this->assertNull($r->getFirstRecordIndex());
    }

    public function testGetFirstRecordIndexNoFirstRows(): void
    {
        $h = RTM::getTemplate('OK')->getHash();
        $h["PROPERTY"] = array(
            "DOMAIN" => array('mydomain1.com', 'mydomain2.com')
        );
        $r = new R(RP::serialize($h));
        $this->assertEquals(0, $r->getFirstRecordIndex());
    }

    public function testGetColumns(): void
    {
        $r = new R("listP0");
        $cols = $r->getColumns();
        $this->assertEquals(6, count($cols));
    }

    public function testGetColumnIndexExists(): void
    {
        $r = new R("listP0");
        $data = $r->getColumnIndex('DOMAIN', 0);
        $this->assertEquals('0-60motorcycletimes.com', $data);
    }

    public function testGetColumnIndexNotExists(): void
    {
        $r = new R("listP0");
        $data = $r->getColumnIndex('COLUMN_NOT_EXISTS', 0);
        $this->assertNull($data);
    }

    public function testGetColumnKeys(): void
    {
        $r = new R("listP0");
        $colKeys = $r->getColumnKeys();
        $this->assertCount(6, $colKeys);
        $this->assertContains('COUNT', $colKeys);
        $this->assertContains('DOMAIN', $colKeys);
        $this->assertContains('FIRST', $colKeys);
        $this->assertContains('LAST', $colKeys);
        $this->assertContains('LIMIT', $colKeys);
        $this->assertContains('TOTAL', $colKeys);
    }

    public function testGetCurrentRecordRows(): void
    {
        $r = new R("listP0");
        $rec = $r->getCurrentRecord();
        $this->assertEquals(array(
            'COUNT' => '2',
            'DOMAIN' => '0-60motorcycletimes.com',
            'FIRST' => '0',
            'LAST' => '1',
            'LIMIT' => '2',
            'TOTAL' => '2701'
        ), $rec->getData());
    }

    public function testGetCurrentRecordNoRows(): void
    {
        $r = new R("OK");
        $this->assertNull($r->getCurrentRecord());
    }

    public function testGetListHash(): void
    {
        $r = new R("listP0");
        $lh = $r->getListHash();
        $this->assertCount(2, $lh["LIST"]);
        $this->assertEquals($lh["meta"]["columns"], $r->getColumnKeys());
        $this->assertEquals($lh["meta"]["pg"], $r->getPagination());
    }

    public function testGetNextRecord(): void
    {
        $r = new R("listP0");
        $rec = $r->getNextRecord();
        $this->assertEquals(array('DOMAIN' => '0-be-s01-0.com'), $rec->getData());
        $this->assertNull($r->getNextRecord());
    }

    public function testGetPagination(): void
    {
        $r = new R("listP0");
        $pager = $r->getPagination();
        $this->assertArrayHasKey('COUNT', $pager);
        $this->assertArrayHasKey('CURRENTPAGE', $pager);
        $this->assertArrayHasKey('FIRST', $pager);
        $this->assertArrayHasKey('LAST', $pager);
        $this->assertArrayHasKey('LIMIT', $pager);
        $this->assertArrayHasKey('NEXTPAGE', $pager);
        $this->assertArrayHasKey('PAGES', $pager);
        $this->assertArrayHasKey('PREVIOUSPAGE', $pager);
        $this->assertArrayHasKey('TOTAL', $pager);
    }

    public function testGetPreviousRecord(): void
    {
        $r = new R("listP0");
        $r->getNextRecord();
        $this->assertEquals(array(
            'COUNT' => '2',
            'DOMAIN' => '0-60motorcycletimes.com',
            'FIRST' => '0',
            'LAST' => '1',
            'LIMIT' => '2',
            'TOTAL' => '2701'
        ), ($r->getPreviousRecord())->getData());
        $this->assertNull($r->getPreviousRecord());
    }

    public function testHasNextPageNoRows(): void
    {
        $r = new R("OK");
        $this->assertEquals(false, $r->hasNextPage());
    }

    public function testHasNextPageRows(): void
    {
        $r = new R("listP0");
        $this->assertEquals(true, $r->hasNextPage());
    }

    public function testHasPreviousPageNoRows1(): void
    {
        $r = new R("OK");
        $this->assertEquals(false, $r->hasPreviousPage());
    }

    public function testHasPreviousPageNoRows2(): void
    {
        $r = new R("listP0");
        $this->assertEquals(false, $r->hasPreviousPage());
    }

    public function testGetLastRecordIndexNoRows(): void
    {
        $r = new R("OK");
        $this->assertNull($r->getLastRecordIndex());
    }

    public function testGetLastRecordIndexNoLastRows(): void
    {
        $h = RTM::getTemplate('OK')->getHash();
        $h["PROPERTY"] = array(
            'DOMAIN' => array('mydomain1.com', 'mydomain2.com')
        );
        $r = new R(RP::serialize($h));
        $this->assertEquals(1, $r->getLastRecordIndex());
    }

    public function testGetNextPageNumberNoRows(): void
    {
        $r = new R("OK");
        $this->assertNull($r->getNextPageNumber());
    }

    public function testGetNextPageNumberRows(): void
    {
        $r = new R("listP0");
        $this->assertEquals(2, $r->getNextPageNumber());
    }

    public function testGetNumberOfPages(): void
    {
        $r = new R("OK");
        $this->assertEquals(0, $r->getNumberOfPages());
    }

    public function testGetPreviousPageNumberNoRows(): void
    {
        $r = new R("OK");
        $this->assertNull($r->getPreviousPageNumber());
    }

    public function testGetPreviousPageNumberRows(): void
    {
        $r = new R("listP0");
        $this->assertNull($r->getPreviousPageNumber());
    }

    public function testRewindRecordList(): void
    {
        $r = new R("listP0");
        $this->assertNull($r->getPreviousRecord());
        $this->assertNotNull($r->getNextRecord());
        $this->assertNull($r->getNextRecord());
        $this->assertNull($r->rewindRecordList()->getPreviousRecord());
    }

    public function testConstructorEmptyRaw(): void
    {
        $r = new R("");
        $this->assertEquals(423, $r->getCode());
        $this->assertEquals("Empty API response. Probably unreachable API end point", $r->getDescription());
    }

    public function testInvalidAPIResponse(): void
    {
        $r = new R("[RESPONSE]\r\ncode=200\r\nqueuetime=0\r\nEOF\r\n");
        $this->assertEquals(423, $r->getCode());
        $this->assertEquals("Invalid API response. Contact Support", $r->getDescription());
    }

    public function testGetHash(): void
    {
        $h = (new R(''))->getHash();
        $this->assertEquals("423", $h["CODE"]);
        $this->assertEquals("Empty API response. Probably unreachable API end point", $h["DESCRIPTION"]);
    }

    public function testGetQueuetimeNo(): void
    {
        $r = new R('');
        $this->assertEquals(0, $r->getQueuetime());
    }

    public function testGetQueuetime(): void
    {
        $r = new R("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response. Probably unreachable API end point\r\nqueuetime=0\r\nEOF\r\n");
        $this->assertEquals(0, $r->getQueuetime());
    }

    public function testGetRuntimeNo(): void
    {
        $r = new R('');
        $this->assertEquals(0, $r->getRuntime());
    }

    public function testGetRuntime(): void
    {
        $r = new R("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response. Probably unreachable API end point\r\nruntime=0.12\r\nEOF\r\n");
        $this->assertEquals(0.12, $r->getRuntime());
    }

    public function testIsPendingNo(): void
    {
        $r = new R('');
        $this->assertEquals(false, $r->isPending());
    }

    public function testIsPending(): void
    {
        $r = new R("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response. Probably unreachable API end point\r\npending=1\r\nEOF\r\n");
        $this->assertEquals(true, $r->isPending());
    }

    public function testIsTmpError(): void
    {
        $r = new R("[RESPONSE]\r\ncode=423\r\ndescription=Empty API response. Probably unreachable API end point\r\nEOF\r\n");
        $this->assertEquals(true, $r->isTmpError());
    }
}
