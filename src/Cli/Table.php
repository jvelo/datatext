<?php

namespace Jvelo\Datatext\Cli;

/**
 * Table to be render in the cli
 *
 * @package Jvelo\Datatext\Cli
 */
class Table {

    private $rows;

    private $numberOfColumns;

    private $columnSizes = [];

    private $options = [];

    private $headers = NULL;

    private $stickyColumns = [];

    /**
     * @param array $rows the table rows.
     * @param array $options the table options. Valid keys :
     *                       - ```headers``` an array with the table headers
     */
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

        if (array_key_exists('stickyColumns', $this->options)) {
            if (is_numeric($this->options['stickyColumns'])) {
                $this->stickyColumns = [ $this->options['stickyColumns'] ];
            }
            else if (is_array($this->options['stickyColumns'])) {
                $this->stickyColumns = $this->options['stickyColumns'];
            }
            else {
                throw new \InvalidArgumentException('Invalid sticky columns definition. Not an array or single numeric value');
            }
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

    /**
     * Main API, renders the table to screen
     *
     * @param array $options the rendering options. Valid keys :
     *                       - ```columnOffset``` the column offset to start rendering at
     */
    public function render($options = []) {
        $columnOffset = 0;
        if (array_key_exists('columnOffset', $options)) {
            if (!is_numeric($options['columnOffset'])) {
                throw new \InvalidArgumentException('Invalid column offset. Not an number');
            }
            $columnOffset = $options['columnOffset'];
        }

        $columns = $this->getColumnsForOffset($columnOffset);

        if (count($columns) === 0) {
            echo PHP_EOL;
            return;
        }

        if (!is_null($this->headers)) {
            $this->renderHeaders($columnOffset, $columns);
        }

        foreach ($this->rows as $row) {
            if ($this->hasMoreRowsOnLeft($columnOffset)) {
                echo '◀ |';
            }

            foreach ($columns as $index) {
                if ($index > 0 || $this->hasMoreRowsOnLeft($columnOffset)) {
                    echo ' ';
                }
                $this->renderCell($row, $index);
            }
            echo PHP_EOL;
        }
    }

    /**
     * @param $offset the offset to get column for
     * @return array the columns to render, including sticky columns, starting at offset
     */
    private function getColumnsForOffset($offset) {
        $columns = $this->stickyColumns;
        for ($i=$offset; $i < $this->numberOfColumns; $i++) {
            if (!in_array($i, $columns)) {
                $columns[]= $i;
            }
        }

        return $columns;
    }

    /**
     * @param integer $offset the column offset for considered
     * @return boolean whether or not rendering this table would have more rows available on the left side
     */
    private function hasMoreRowsOnLeft($offset) {
        return $offset > 0 && array_reduce($this->stickyColumns, function($carry, $item) use ($offset) {
            return $carry && $item < $offset;
        }, true);
    }

    /**
     * Renders a cell from a row
     *
     * @param array $row the row the cell is in
     * @param integer $index the index of the cell in the rowrow
     */
    private function renderCell($row, $index)
    {
        echo $row[$index];
        echo str_repeat(' ', ($this->columnSizes[$index] - mb_strwidth($row[$index])));
        if ($index < $this->numberOfColumns - 1) {
            echo ' |';
        }
    }

    /**
     * Render the table headers
     *
     * @param integer $offset the offset headers are requested to render at
     * @param array $columns an array of header column indexes to render
     */
    private function renderHeaders($offset, $columns)
    {
        if ($this->hasMoreRowsOnLeft($offset) > 0) {
            echo '◀ |';
        }
        foreach($columns as $i) {
            if (array_key_exists($i, $this->headers)) {
                if ($i > 0 || $this->hasMoreRowsOnLeft($offset)) {
                    echo ' ';
                }
                $this->renderCell($this->headers, $i);
            }
        }
        echo PHP_EOL;
        if ($this->hasMoreRowsOnLeft($offset) > 0) {
            echo '--+';
        }
        foreach($columns as $i) {
            if ($i > 0 || $this->hasMoreRowsOnLeft($offset)) {
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