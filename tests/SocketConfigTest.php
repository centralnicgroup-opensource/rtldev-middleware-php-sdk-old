<?php
declare(strict_types=1);

namespace HEXONETTEST;

use PHPUnit\Framework\TestCase;
use HEXONET\SocketConfig as SC;

final class SocketConfigTest extends TestCase
{
    public function testGetPOSTData()
    {
        $d = (new SC())->getPOSTData();
        $this->assertEmpty($d);
    }
}
