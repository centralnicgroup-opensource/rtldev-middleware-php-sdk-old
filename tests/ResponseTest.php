<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HEXONET\Response as R;
use HEXONET\ResponseTemplateManager as RTM;
use HEXONET\ResponseParser as RP;

final class ResponseTest extends TestCase
{
    public static $rtm;

    public static function setupBeforeClass() {
        self::$rtm = RTM::getInstance();
        self::$rtm->addTemplate("OK", self::$rtm->generateTemplate("200", "Command completed successfully"));
        self::$rtm->addTemplate("listP0", "[RESPONSE]\r\nPROPERTY[TOTAL][0]=2701\r\nPROPERTY[FIRST][0]=0\r\nPROPERTY[DOMAIN][0]=0-60motorcycletimes.com\r\nPROPERTY[DOMAIN][1]=0-be-s01-0.com\r\nPROPERTY[COUNT][0]=2\r\nPROPERTY[LAST][0]=1\r\nPROPERTY[LIMIT][0]=2\r\nDESCRIPTION=Command completed successfully\r\nCODE=200\r\nQUEUETIME=0\r\nRUNTIME=0.023\r\nEOF\r\n");
    }

    public static function tearDownAfterClass() {
        self::$rtm = null;
    }

    public function test_getCurrentPageNumberEntries() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertEquals(1, $r->getCurrentPageNumber());
    }

    public function test_getCurrentPageNumberNoEntries() {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getCurrentPageNumber());
    }

    public function test_getFirstRecordIndexNoFirstNoRows() {
        $r = new R(self::$rtm->getTemplate('OK')->getPlain());
        $this->assertNull($r->getFirstRecordIndex());
    }

    public function test_getFirstRecordIndexNoFirstRows() {
        $h = self::$rtm->getTemplate('OK')->getHash();
        $h["PROPERTY"] = array(
            "DOMAIN" => array('mydomain1.com', 'mydomain2.com')
        );
        $r = new R(RP::serialize($h));
        $this->assertEquals(0, $r->getFirstRecordIndex());
    }

    public function test_getColumns() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $cols = $r->getColumns();
        $this->assertEquals(6, count($cols));
    }

    public function test_getColumnIndexExists() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $data = $r->getColumnIndex('DOMAIN', 0);
        $this->assertEquals('0-60motorcycletimes.com', $data);
    }

    public function test_getColumnIndexNotExists() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $data = $r->getColumnIndex('COLUMN_NOT_EXISTS', 0);
        $this->assertNull($data);
    }

    public function test_getColumnKeys() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $colKeys = $r->getColumnKeys();
        $this->assertCount(6, $colKeys);
        $this->assertContains('COUNT', $colKeys);
        $this->assertContains('DOMAIN', $colKeys);
        $this->assertContains('FIRST', $colKeys);
        $this->assertContains('LAST', $colKeys);
        $this->assertContains('LIMIT', $colKeys);
        $this->assertContains('TOTAL', $colKeys);
    }

    public function test_getCurrentRecordRows() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
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

    public function test_getCurrentRecordNoRows() {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $rec = $r->getCurrentRecord();
        $this->assertNull($rec);
    }

    public function test_getListHash() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $lh = $r->getListHash();
        $this->assertCount(2, $lh["LIST"]);
        $this->assertEquals($lh["meta"]["columns"], $r->getColumnKeys());
        $this->assertEquals($lh["meta"]["pg"], $r->getPagination());
    }

    public function test_getNextRecord() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $rec = $r->getNextRecord();
        $this->assertEquals(array('DOMAIN'=>'0-be-s01-0.com'), $rec->getData());
        $rec = $r->getNextRecord();
        $this->assertNull($rec);
    }

    public function test_getPagination() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
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

    public function test_getPreviousRecord() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $r->getNextRecord();
        $rec = $r->getPreviousRecord();
        $this->assertEquals(array(
            'COUNT' => '2',
            'DOMAIN' => '0-60motorcycletimes.com',
            'FIRST' => '0',
            'LAST' => '1',
            'LIMIT' => '2',
            'TOTAL' => '2701'
        ), $rec->getData());
        $rec = $r->getPreviousRecord();
        $this->assertNull($rec);
    }

    public function test_hasNextPageNoRows() {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertEquals(false, $r->hasNextPage());
    }

    public function test_hasNextPageRows() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertEquals(true, $r->hasNextPage());
    }

    public function test_hasPreviousPageNoRows1() {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertEquals(false, $r->hasPreviousPage());
    }

    public function test_hasPreviousPageNoRows2() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertEquals(false, $r->hasPreviousPage());
    }

    public function test_getLastRecordIndexNoRows() {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getLastRecordIndex());
    }

    public function test_getLastRecordIndexNoLastRows() {
        $h = self::$rtm->getTemplate('OK')->getHash();
        $h["PROPERTY"] = array(
            'DOMAIN' => array('mydomain1.com', 'mydomain2.com')
        );
        $r = new R(RP::serialize($h));
        $this->assertEquals(1, $r->getLastRecordIndex());
    }

    public function test_getNextPageNumberNoRows() {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getNextPageNumber());
    }

    public function test_getNextPageNumberRows() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertEquals(2, $r->getNextPageNumber());
    }

    public function test_getNumberOfPages() {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertEquals(0, $r->getNumberOfPages());
    }

    public function test_getPreviousPageNumberNoRows() {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getPreviousPageNumber());
    }

    public function test_getPreviousPageNumberRows() {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertNull($r->getPreviousPageNumber());
    }

    public function test_rewindRecordList() {
        $r = new R(self::$rtm->getTemplate('listP0')->getPlain());
        $this->assertNull($r->getPreviousRecord());
        $this->assertNotNull($r->getNextRecord());
        $this->assertNull($r->getNextRecord());
        $this->assertNull($r->rewindRecordList()->getPreviousRecord());
    }
}