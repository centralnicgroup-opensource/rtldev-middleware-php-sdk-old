<?php

//declare(strict_types=1);

namespace HEXONETTEST;

final class ColumnTest extends \PHPUnit\Framework\TestCase
{
    public function testGetKey(): void
    {
        $col = new \HEXONET\Column("DOMAIN", ["mydomain1.com", "mydomain2.com", "mydomain3.com"]);
        $this->assertEquals("DOMAIN", $col->getKey());
    }
}
