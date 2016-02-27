<?php

namespace Jvelo\Datatext\Cli\Tables;

use Jvelo\Datatext\Cli\Navigable;
use Hoa\Console\Readline\Readline;
use Hoa\Console\Cursor;

class NavigableTable implements Navigable
{
    private $readline;

    private $table;

    private $columnOffset = 0;

    function __construct($table)
    {
        $this->table = $table;
    }

    public function render()
    {
        $this->renderInternal();
        Cursor::setStyle('▋', true);
        do {
            $this->readline = new Readline;
            $this->readline->addMapping('j', xcallable($this, '_bindJ'));
            $this->readline->addMapping('k', xcallable($this, '_bindK'));
            $line = $this->readline->readLine(' ');
        } while (false !== $line && 'quit' !== $line);
    }

    public function renderInternal() {
        $this->table->render([
            'columnOffset' => $this->columnOffset
        ]);

        Cursor::colorize('b fg(yellow) bg(blue)');
        echo "Navigate with ⇦ ⇨ ; use ':' to enter a command";
        Cursor::colorize('!b fg(default) bg(default)');
        echo PHP_EOL;
    }


    public function _bindJ() {
        if ($this->columnOffset == 0) {
            Cursor::bip();
        }
        else {
            $this->columnOffset--;
            $this->renderInternal();
        }
        return Readline::STATE_NO_ECHO;
    }

    public function _bindK() {
        if ($this->columnOffset == $this->table->getNumberOfColumns() - 1) {
            Cursor::bip();
        } else {
            $this->columnOffset++;
            $this->renderInternal();
        }
        return Readline::STATE_NO_ECHO;
    }
}