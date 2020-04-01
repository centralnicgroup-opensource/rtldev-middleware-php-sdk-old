<?php
declare(strict_types=1);

namespace HEXONETTEST;

use PHPUnit\Framework\TestCase;
use HEXONET\Response as R;
use HEXONET\ResponseTemplateManager as RTM;
use HEXONET\ResponseParser as RP;

final class ResponseTest extends TestCase
{
    public static $rtm;

    public static function setupBeforeClass(): void
    {
        self::$rtm = RTM::getInstance();
        self::$rtm->addTemplate("OK", self::$rtm->generateTemplate("200", "Command completed successfully"))
                  ->addTemplate("listP0", "[RESPONSE]\r\nPROPERTY[TOTAL][0]=2701\r\nPROPERTY[FIRST][0]=0\r\nPROPERTY[DOMAIN][0]=0-60motorcycletimes.com\r\nPROPERTY[DOMAIN][1]=0-be-s01-0.com\r\nPROPERTY[COUNT][0]=2\r\nPROPERTY[LAST][0]=1\r\nPROPERTY[LIMIT][0]=2\r\nDESCRIPTION=Command completed successfully\r\nCODE=200\r\nQUEUETIME=0\r\nRUNTIME=0.023\r\nEOF\r\n");
    }

    public static function tearDownAfterClass(): void
    {
        self::$rtm = null;
    }

    public function testPlaceHolderReplacements()
    {
        // ensure no vars are returned in response, just in case no place holder replacements are provided
        $r = new R("");
        $this->assertEquals(0, preg_match("/\{[A-Z_]+\}/", $r->getDescription()), "case 1");

        // ensure variable replacements are correctly handled in case place holder replacements are provided
        $r = new R("", ["COMMAND" => "StatusAccount"], ["CONNECTION_URL" => "123HXPHFOUND123"]);
        $this->assertEquals(true, preg_match("/123HXPHFOUND123/", $r->getDescription()), "case 2");
    }

    public function testGetCurrentPageNumberEntries()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertEquals(1, $r->getCurrentPageNumber());
    }

    public function testGetCurrentPageNumberNoEntries()
    {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getCurrentPageNumber());
    }

    public function testGetFirstRecordIndexNoFirstNoRows()
    {
        $r = new R(self::$rtm->getTemplate('OK')->getPlain());
        $this->assertNull($r->getFirstRecordIndex());
    }

    public function testGetFirstRecordIndexNoFirstRows()
    {
        $h = self::$rtm->getTemplate('OK')->getHash();
        $h["PROPERTY"] = array(
            "DOMAIN" => array('mydomain1.com', 'mydomain2.com')
        );
        $r = new R(RP::serialize($h));
        $this->assertEquals(0, $r->getFirstRecordIndex());
    }

    public function testGetColumns()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $cols = $r->getColumns();
        $this->assertEquals(6, count($cols));
    }

    public function testGetColumnIndexExists()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $data = $r->getColumnIndex('DOMAIN', 0);
        $this->assertEquals('0-60motorcycletimes.com', $data);
    }

    public function testGetColumnIndexNotExists()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $data = $r->getColumnIndex('COLUMN_NOT_EXISTS', 0);
        $this->assertNull($data);
    }

    public function testGetColumnKeys()
    {
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

    public function testGetCurrentRecordRows()
    {
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

    public function testGetCurrentRecordNoRows()
    {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getCurrentRecord());
    }

    public function testGetListHash()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $lh = $r->getListHash();
        $this->assertCount(2, $lh["LIST"]);
        $this->assertEquals($lh["meta"]["columns"], $r->getColumnKeys());
        $this->assertEquals($lh["meta"]["pg"], $r->getPagination());
    }

    public function testGetNextRecord()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $rec = $r->getNextRecord();
        $this->assertEquals(array('DOMAIN'=>'0-be-s01-0.com'), $rec->getData());
        $this->assertNull($r->getNextRecord());
    }

    public function testGetPagination()
    {
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

    public function testGetPreviousRecord()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
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

    public function testHasNextPageNoRows()
    {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertEquals(false, $r->hasNextPage());
    }

    public function testHasNextPageRows()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertEquals(true, $r->hasNextPage());
    }

    public function testHasPreviousPageNoRows1()
    {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertEquals(false, $r->hasPreviousPage());
    }

    public function testHasPreviousPageNoRows2()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertEquals(false, $r->hasPreviousPage());
    }

    public function testGetLastRecordIndexNoRows()
    {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getLastRecordIndex());
    }

    public function testGetLastRecordIndexNoLastRows()
    {
        $h = self::$rtm->getTemplate('OK')->getHash();
        $h["PROPERTY"] = array(
            'DOMAIN' => array('mydomain1.com', 'mydomain2.com')
        );
        $r = new R(RP::serialize($h));
        $this->assertEquals(1, $r->getLastRecordIndex());
    }

    public function testGetNextPageNumberNoRows()
    {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getNextPageNumber());
    }

    public function testGetNextPageNumberRows()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertEquals(2, $r->getNextPageNumber());
    }

    public function testGetNumberOfPages()
    {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertEquals(0, $r->getNumberOfPages());
    }

    public function testGetPreviousPageNumberNoRows()
    {
        $r = new R(self::$rtm->getTemplate("OK")->getPlain());
        $this->assertNull($r->getPreviousPageNumber());
    }

    public function testGetPreviousPageNumberRows()
    {
        $r = new R(self::$rtm->getTemplate("listP0")->getPlain());
        $this->assertNull($r->getPreviousPageNumber());
    }

    public function testRewindRecordList()
    {
        $r = new R(self::$rtm->getTemplate('listP0')->getPlain());
        $this->assertNull($r->getPreviousRecord());
        $this->assertNotNull($r->getNextRecord());
        $this->assertNull($r->getNextRecord());
        $this->assertNull($r->rewindRecordList()->getPreviousRecord());
    }
}
