<?php

namespace Jvelo\Datatext\Cli;

use Hoa\Console\Readline\Readline;
use Hoa\Console\Cursor;
use Hoa\Console\Window;
use Faker\Factory;

class Cli {

    private $readline;
    private $faker;

    function __construct()
    {
        $this->readline = new Readline;
        $this->faker = Factory::create();
    }

    public function listen() {
        Cursor::setStyle('▋', true);
        do {
            Cursor::colorize('b fg(yellow) bg(blue)');
            echo '◼ datatext > ';
            Cursor::colorize('!b fg(default) bg(default)');
            $line = $this->readline->readLine(' ');

            if ($line === 'fake') {
                $rows = [];
                $size = Window::getSize();
                for ($i=0; $i < $size['y'] - 2; $i++) {
                    $row = [];
                    for ($j=0; $j < 5; $j++) {
                        $row[]= $this->faker->name;
                    }
                    $rows[]= $row;
                }
                $table = new Table($rows);
                $table->render();
            }

            else {
                echo '< ', $line, "\n";
            }

        } while (false !== $line && 'quit' !== $line);
    }
}