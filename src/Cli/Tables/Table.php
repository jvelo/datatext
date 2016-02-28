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
 * Table to be render in the cli
 *
 * @package Jvelo\Datatext\Cli\Tables
 */
class Table {

    private $moreLeftRowsSymbol = '◀';

    private $moreRightRowsSymbol = '▶';

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

        $this->processOptions();

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

        $renderingOptions = new RenderingOptions($options);

        $columns = $this->getColumnsForOffset($renderingOptions->getColumnOffset());

        if (count($columns) === 0) {
            echo PHP_EOL;
            return;
        }

        if (!is_null($this->headers)) {
            $this->renderHeaders($columns, $renderingOptions);
        }

        foreach ($this->rows as $row) {
            $offsetX = 0;
            if ($this->hasMoreRowsOnLeft($renderingOptions->getColumnOffset())) {
                echo $this->moreLeftRowsSymbol;
                $offsetX += 1;
            }

            foreach ($columns as $index) {
                if (($index > 0 || $this->hasMoreRowsOnLeft($renderingOptions->getColumnOffset()))
                    && $offsetX < $renderingOptions->getScreenEstate()['x']) {
                    echo ' ';
                    $offsetX += 1;
                }
                $offsetX += $this->renderCell($row, $index, $renderingOptions, $offsetX);
            }
            echo PHP_EOL;
        }
    }

    public function getNumberOfColumns()
    {
        return $this->numberOfColumns;
    }

    /**
     * @param integer $offset the offset to get column for
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
     * @param integer $index the index of the cell in the row
     * @param RenderingOptions $renderingOptions the rendering options associated with this rendering
     * @param integer offsetX the number of chars written on the line before this cell
     *
     * @return integer the number of chars written by this cell;
     */
    private function renderCell($row, $index, RenderingOptions $renderingOptions, $offsetX = 0)
    {
        if ($offsetX >= $renderingOptions->getScreenEstate()['x']) {
            return 0;
        }

        if ($renderingOptions->getScreenEstate()['x'] - 2 < $offsetX + $this->columnSizes[$index]) {
            $padding = $renderingOptions->getScreenEstate()['x'] - $offsetX - 2 - mb_strwidth($row[$index]);
            $overflowRight = true;
        } else {
            $padding = $this->columnSizes[$index] - mb_strwidth($row[$index]);
            $overflowRight = false;
        }
        $cell = $overflowRight ?
            mb_substr($row[$index], 0, $renderingOptions->getScreenEstate()['x'] - $offsetX - 2) :
            $row[$index];
        if ($padding > 0) {
            $cell .= str_repeat(' ', $padding);
        }
        if ($index < $this->numberOfColumns - 1 && !$overflowRight) {
            $cell .= ' |';
        }
        else if ($overflowRight) {
            $cell .= " $this->moreRightRowsSymbol";
        }
        echo $cell;
        return mb_strwidth($cell);
    }

    /**
     * Render the table headers
     *
     * @param array $columns an array of header column indexes to render
     * @param RenderingOptions $renderingOptions the rendering options associated with this rendering
     */
    private function renderHeaders($columns, RenderingOptions $renderingOptions)
    {
        $offsetX = 0;
        if ($this->hasMoreRowsOnLeft($renderingOptions->getColumnOffset()) > 0) {
            echo $this->moreLeftRowsSymbol;
            $offsetX += 1;
        }
        foreach($columns as $i) {
            if (array_key_exists($i, $this->headers)) {
                if (($i > 0 || $this->hasMoreRowsOnLeft($renderingOptions->getColumnOffset()))
                    && $offsetX < $renderingOptions->getScreenEstate()['x']) {
                    echo ' ';
                    $offsetX += 1;
                }
                $offsetX += $this->renderCell($this->headers, $i, $renderingOptions, $offsetX);
            }
        }
        echo PHP_EOL;
        if ($this->hasMoreRowsOnLeft($renderingOptions->getColumnOffset()) > 0) {
            echo "$this->moreLeftRowsSymbol ";
        }
        $offsetX = 0;
        foreach($columns as $i) {
            if ($offsetX >= $renderingOptions->getScreenEstate()['x']) {
                continue;
            }
            if ($i > 0) {
                echo '-';
                $offsetX += 1;
            }
            $lineLength = min($renderingOptions->getScreenEstate()['x'] - $offsetX - 2, $this->columnSizes[$i] + ($i < $this->numberOfColumns - 1 ? 1 : 0));
            if ($lineLength > 0) {
                echo str_repeat('-', $lineLength);
                $offsetX += $lineLength;

                if ($i < $this->numberOfColumns - 1 && $offsetX < $renderingOptions->getScreenEstate()['x'] - 2) {
                    echo '+';
                    $offsetX += 1;
                }
            }
            if ($offsetX >= $renderingOptions->getScreenEstate()['x'] - 2) {
                echo " $this->moreRightRowsSymbol";
                $offsetX += 2;
            }
        }
        echo PHP_EOL;
    }

    /**
     * Unpack the options array in the private variables of this class.
     */
    private function processOptions()
    {
        if (array_key_exists('headers', $this->options)) {
            if (!is_array($this->options['headers'])) {
                throw new \InvalidArgumentException('Invalid headers. Not an array');
            }
            $this->headers = $this->options['headers'];
        }

        if (array_key_exists('stickyColumns', $this->options)) {
            if (is_numeric($this->options['stickyColumns'])) {
                $this->stickyColumns = [$this->options['stickyColumns']];
            } else if (is_array($this->options['stickyColumns'])) {
                $this->stickyColumns = $this->options['stickyColumns'];
            } else {
                throw new \InvalidArgumentException('Invalid sticky columns definition. Not an array or single numeric value');
            }
        }

        if (array_key_exists('symbols', $this->options)) {
            if (!is_array($this->options['symbols'])) {
                throw new \InvalidArgumentException('Invalid symbols option. Not an array');
            }
            $symbols = $this->options['symbols'];
            if (array_key_exists('moreLeftRows', $symbols)) {
                if (!is_string($symbols['moreLeftRows'])) {
                    throw new \InvalidArgumentException('Invalid moreLeftRows symbol. Not a string');
                }
                $this->moreLeftRowsSymbol = $symbols['moreLeftRows'];
            }
            if (array_key_exists('moreRightRows', $symbols)) {
                if (!is_string($symbols['moreRightRows'])) {
                    throw new \InvalidArgumentException('Invalid moreRightRows symbol. Not a string');
                }
                $this->moreRightRowsSymbol = $symbols['moreRightRows'];
            }
        }
    }
}