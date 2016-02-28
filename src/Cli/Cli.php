<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Cli;

use Hoa\Console\Readline\Readline;
use Hoa\Console\Cursor;
use Hoa\Console\Window;
use Faker\Factory;
use Jvelo\Datatext\Cli\Tables\Table;
use Jvelo\Datatext\Cli\Tables\NavigableTable;

class Cli
{
    private $readline;
    private $faker;

    function __construct()
    {
        $this->readline = new Readline;
        $this->faker = Factory::create();
    }

    public function listen()
    {
        Cursor::setStyle('▋', true);
        do {
            Cursor::colorize('b fg(yellow) bg(blue)');
            echo '◼ datatext > ';
            Cursor::colorize('!b fg(default) bg(default)');
            $line = $this->readline->readLine(' ');

            if ($line === 'fake') {
                $rows = [];
                $size = Window::getSize();
                for ($i = 0; $i < $size['y'] - 4; $i++) {
                    $row = [];
                    for ($j = 0; $j < 10; $j++) {
                        $row[] = $this->faker->name;
                    }
                    $rows[] = $row;
                }
                $table = new Table($rows, ['headers' => ['a', 'b', 'c', 'd', 'e']]);
                $navigable = new NavigableTable($table);
                $navigable->render();
            } else {
                echo '< ', $line, "\n";
            }

        } while (false !== $line && 'quit' !== $line);
    }
}