<?php

namespace Jvelo\Datatext\Cli;

class Table {

    private $rows;

    private $numberOfColumns;

    private $columnSizes = [];

    private $options = [];

    private $headers = NULL;

    function __construct($rows, $options = [])
    {
        if (!is_array($rows)) {
            throw new \InvalidArgumentException('Invalid rows. Not an array');
        }

        $this->rows = $rows;
        $this->options = $options;

        if (array_key_exists('headers', $this->options)) {
            if (!is_array($this->options['headers'])) {
                throw new \InvalidArgumentException('Invalid headers. Not an array');
            }
            $this->headers = $this->options['headers'];
        }

        $this->numberOfColumns = array_reduce($this->rows, function($max, $row) {
            return max(count($row), $max);
        }, 0);

        $this->columnSizes = [];
        for ($i=0; $i < $this->numberOfColumns; $i++) {
            $this->columnSizes[]= array_reduce(array_merge($this->rows, is_null($this->headers) ? [] : [$this->headers]),
                function($size, $row) use ($i) {
                    return max($size, array_key_exists($i, $row) ? mb_strwidth($row[$i]) : 1);
            }, 0);
        }
    }

    public function render($options = []) {
        if (!is_null($this->headers)) {
            $this->renderHeaders();
        }

        foreach ($this->rows as $row) {
            for ($i=0; $i < $this->numberOfColumns; $i++) {
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
        if ($index < $this->numberOfColumns - 1) {
            echo ' |';
        }
    }

    private function renderHeaders()
    {
        for ($i = 0; $i < $this->numberOfColumns; $i++) {
            if (array_key_exists($i, $this->headers)) {
                $this->outputCell($this->headers, $i);
            }
        }
        echo PHP_EOL;
        for ($i = 0; $i < $this->numberOfColumns; $i++) {
            if ($i > 0) {
                echo '-';
            }
            echo str_repeat('-', $this->columnSizes[$i] + ($i < $this->numberOfColumns - 1 ? 1 : 0));
            if ($i < $this->numberOfColumns - 1) {
                echo '+';
            }
        }
        echo PHP_EOL;
    }
}