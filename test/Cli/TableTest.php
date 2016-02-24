<?php

namespace Jvelo\Datatext\Cli;

/**
 * Test for the CLI table formatter
 *
 * @package Jvelo\Datatext\Cli
 * @group cli
 *
 */
class TableTest extends \PHPUnit_Framework_TestCase {

    public function testRenderSimpleTable() {
        $rows = [
            ['a', 'b'],
            ['c', 'd']
        ];
        $table = new Table($rows);
        $table->render();
        $this->expectOutputString("a | b\nc | d\n");
    }

    public function testRenderTableWithHeaders() {
        $rows = [
            ['1079', 'Musical Offering', '1747-07-07'],
            ['1080', 'The Art of Fugue', '1742–1750']
        ];
        $headers = ['BVW', 'Name', 'Date'];
        $table = new Table($rows, ['headers' => $headers]);
        $table->render();
        $expected = <<< EXPECTED
BVW  | Name             | Date\x20\x20\x20\x20\x20\x20
-----+------------------+-----------
1079 | Musical Offering | 1747-07-07
1080 | The Art of Fugue | 1742–1750\x20

EXPECTED;
        $this->expectOutputString($expected);

    }
}