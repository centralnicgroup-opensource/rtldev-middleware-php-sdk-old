<?php
//declare(strict_types=1);

namespace HEXONETTEST;

use \HEXONET\SocketConfig as SC;

final class SocketConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testGetPOSTData()
    {
        $d = (new SC())->getPOSTData();
        $this->assertEmpty($d);
    }
}
