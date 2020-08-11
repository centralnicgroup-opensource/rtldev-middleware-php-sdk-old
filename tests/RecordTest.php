<?php

//declare(strict_types=1);

namespace HEXONETTEST;

final class RecordTest extends \PHPUnit\Framework\TestCase
{
    public function testGetData(): void
    {
        $d = array(
            'DOMAIN' => 'mydomain.com',
            'RATING' => '1',
            'RNDINT' => '321',
            'SUM'    => '1'
        );
        $rec = new \HEXONET\Record($d);
        $this->assertEquals($d, $rec->getData());
    }

    public function testGetDataByKey(): void
    {
        $rec = new \HEXONET\Record(array(
            'DOMAIN' => 'mydomain.com',
            'RATING' => '1',
            'RNDINT' => '321',
            'SUM'    => '1'
        ));
        $this->assertNull($rec->getDataByKey('KEYNOTEXISTING'));
    }
}
