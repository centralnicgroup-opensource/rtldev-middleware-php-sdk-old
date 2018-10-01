<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HEXONET\SocketConfig as SC;

final class SocketConfigTest extends TestCase
{
    public function test_getPOSTData() {
        $d = (new SC())->getPOSTData();
        $this->assertEmpty($d);
    }
}