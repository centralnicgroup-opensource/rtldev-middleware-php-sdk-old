<?php
declare(strict_types=1);

namespace HEXONETTEST;

use PHPUnit\Framework\TestCase;
use HEXONET\Record as R;

final class RecordTest extends TestCase
{
    public function testGetData()
    {
        $d = array(
            'DOMAIN' => 'mydomain.com',
            'RATING' => '1',
            'RNDINT' => '321',
            'SUM'    => '1'
        );
        $rec = new R($d);
        $this->assertEquals($d, $rec->getData());
    }

    public function testGetDataByKey()
    {
        $rec = new R(array(
            'DOMAIN' => 'mydomain.com',
            'RATING' => '1',
            'RNDINT' => '321',
            'SUM'    => '1'
        ));
        $this->assertNull($rec->getDataByKey('KEYNOTEXISTING'));
    }
}
