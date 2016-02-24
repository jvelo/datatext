<?php

namespace Jvelo\Datatext\Cli;

class Table {

    private $rows;

    private $totalNumberOfColumns;

    private $columnSizes = [];

    private $options = [];

    function __construct($rows, $options = [])
    {
        $this->rows = $rows;
        $this->options = $options;


        $this->totalNumberOfColumns = array_reduce($this->rows, function($max, $row) {
            return max(count($row), $max);
        }, 0);

        $this->columnSizes = [];
        for ($i=0; $i < $this->totalNumberOfColumns; $i++) {
            $this->columnSizes[]= array_reduce($this->rows, function($size, $row) use ($i) {
                return max($size, array_key_exists($i, $row) ? mb_strwidth($row[$i]) : 1);
            }, 0);
        }
    }

    public function render($options = []) {
        if (array_key_exists('headers', $this->options)) {
            for ($i=0; $i < $this->totalNumberOfColumns; $i++) {
                if (array_key_exists($i, $this->options['headers'])) {
                    $this->outputCell($this->options['headers'], $i);
                }
            }
            echo PHP_EOL;
            for ($i=0; $i < $this->totalNumberOfColumns; $i++) {
                if ($i > 0) {
                    echo '-';
                }
                echo str_repeat('-', $this->columnSizes[$i] + ($i < $this->totalNumberOfColumns - 1 ? 1 : 0));
                if ($i < $this->totalNumberOfColumns - 1) {
                    echo '+';
                }
            }
            echo PHP_EOL;
        }

        foreach ($this->rows as $row) {
            for ($i=0; $i < $this->totalNumberOfColumns; $i++) {
                $this->outputCell($row, $i);
            }
            echo PHP_EOL;
        }
    }

    /**
     * @param $row
     * @param $index
     */
    private function outputCell($row, $index)
    {
        if ($index > 0) {
            echo ' ';
        }
        echo $row[$index];
        echo str_repeat(' ', ($this->columnSizes[$index] - mb_strwidth($row[$index])));
        if ($index < $this->totalNumberOfColumns - 1) {
            echo ' |';
        }
    }
}