<?php

namespace Jvelo\Datatext\Cli;

class Table {

    private $rows;

    function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function render() {

        $columns = array_reduce($this->rows, function($max, $row) {
            return max(count($row), $max);
        }, 0);

        $columnSizes = [];

        for ($i=0; $i < $columns; $i++) {
            $columnSizes[]= array_reduce($this->rows, function($size, $row) use ($i) {
                return max($size, array_key_exists($i, $row) ? mb_strwidth($row[$i]) : 1);
            }, 0);
        }

        foreach ($this->rows as $row) {
            for ($i=0; $i < $columns; $i++) {
                if ($i > 0) {
                    echo ' ';
                }
                echo $row[$i];
                echo str_repeat(' ', ($columnSizes[$i] - mb_strwidth($row[$i])));
                echo '|';
            }
            echo PHP_EOL;
        }
    }
}