<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Cli\Tables;

/**
 * Test for the CLI table formatter
 *
 * @package Jvelo\Datatext\Cli\Tables
 * @group cli
 *
 */
class TableTest extends \PHPUnit_Framework_TestCase
{

    public function testRenderSimpleTable()
    {
        $rows = [
            ['a', 'b'],
            ['c', 'd']
        ];
        $table = new Table($rows);
        $table->render();
        $this->expectOutputString("a | b\nc | d\n");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderTableWithInvalidRows() {
        $rows = 'toto';
        new Table($rows);
    }

    public function testRenderTableWithHeaders()
    {
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

    public function testRenderTableWithHeaderLargerThanColumns()
    {
        $rows = [
            ['1', '2'],
            ['3', '4']
        ];
        $headers = ['AB', 'CD'];
        $table = new Table($rows, ['headers' => $headers]);
        $table->render();
        $expected = <<< EXPECTED
AB | CD
---+---
1  | 2\x20
3  | 4\x20

EXPECTED;
        $this->expectOutputString($expected);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderTableWithInvalidHeaders() {
        $rows = [
            ['1', '2'],
            ['3', '4']
        ];
        $headers = -1;
        new Table($rows, ['headers' => $headers]);
    }

    public function testRenderTableWithLeftOffset() {
        $rows = [
            ['Wolverine', 'Healing'],
            ['Dream Girl', 'Clairvoyance']
        ];
        $headers = ['Name', 'Power'];
        $table = new Table($rows, ['headers' => $headers]);
        $table->render(['columnOffset' => 1]);
        $expected = <<< EXPECTED
◀ Power\x20\x20\x20\x20\x20\x20\x20
◀ -------------
◀ Healing\x20\x20\x20\x20\x20
◀ Clairvoyance

EXPECTED;
        $this->expectOutputString($expected);
    }

    public function testRenderTableWithLeftOffsetAndCustomMoreLeftRowsSymbol() {
        $rows = [
            ['Wolverine', 'Healing'],
            ['Dream Girl', 'Clairvoyance']
        ];
        $headers = ['Name', 'Power'];
        $table = new Table($rows, [
            'headers' => $headers,
            'symbols' => [
                'moreLeftRows' => '<'
            ]
        ]);
        $table->render(['columnOffset' => 1]);
        $expected = <<< EXPECTED
< Power\x20\x20\x20\x20\x20\x20\x20
< -------------
< Healing\x20\x20\x20\x20\x20
< Clairvoyance

EXPECTED;
        $this->expectOutputString($expected);
    }

    public function testRenderTableWithStickyColumn() {
        $rows = [
            ['Peter', 'Dustin Hoffman', 'Jean-Claude Montalban'],
            ['Steven', 'Robert Redford', 'Patrick Guillemin'],
            ['Dave', 'Paul Newman', 'Patrick Guillemin']
        ];
        $headers = ['Character', 'Actor', 'Voice'];
        $table = new Table($rows, [
            'headers' => $headers,
            'stickyColumns' => [ 0 ]
        ]);
        $table->render(['columnOffset' => 2]);
        $expected = <<< EXPECTED
◀ Character | Voice\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20
◀ ----------+----------------------
◀ Peter     | Jean-Claude Montalban
◀ Steven    | Patrick Guillemin\x20\x20\x20\x20
◀ Dave      | Patrick Guillemin\x20\x20\x20\x20

EXPECTED;
        $this->expectOutputString($expected);
    }

    public function testRenderTableLargerThanScreenEstate() {
        $rows = [
            ['TY', 'KT Rolster', 'Terran'],
            ['Maru', 'Jin Air Green Wings', 'Terran']
        ];
        $headers = ['Player', 'Team', 'Race'];
        $table = new Table($rows, [ 'headers' => $headers ]);
        $table->render(['screenEstate' => [
            'x' => 13
        ]]);
        $expected = <<< EXPECTED
Player | Te ▶
-------+--- ▶
TY     | KT ▶
Maru   | Ji ▶

EXPECTED;

        $this->assertEquals(str_split($expected), str_split($this->getActualOutput()));
        $this->expectOutputString($expected);
    }

}