<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HEXONET\Column as C;

final class ColumnTest extends TestCase
{
    public function test_getKey() {
        $col = new C('DOMAIN', ['mydomain1.com', 'mydomain2.com', 'mydomain3.com']);
        $this->assertEquals('DOMAIN', $col->getKey());
    }
}