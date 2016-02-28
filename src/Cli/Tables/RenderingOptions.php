<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Cli\Tables;

use Hoa\Console\Window;

class RenderingOptions
{
    private $screenEstate;

    private $columnOffset = 0;

    function __construct($options = [])
    {
        if (array_key_exists('columnOffset', $options)) {
            if (!is_integer($options['columnOffset'])) {
                throw new \InvalidArgumentException('Invalid column offset. Not an integer');
            }
            $this->columnOffset = $options['columnOffset'];
        }

        $this->screenEstate = Window::getSize();
        if (array_key_exists('screenEstate', $options)) {
            foreach (['x', 'y'] as $dimension) {
                if (array_key_exists($dimension, $options['screenEstate'])) {
                    if (!is_integer($options['screenEstate'][$dimension])) {
                        throw new \InvalidArgumentException('Invalid screen estate dimension. Not an integer');
                    }
                    $this->screenEstate[$dimension] = $options['screenEstate'][$dimension];
                }
            }
        }
    }

    /**
     * @return array the screen estate for this rendering as an array of 'x' and 'y'
     */
    public function getScreenEstate()
    {
        return $this->screenEstate;
    }

    /**
     * @return integer the column offset for this rendering
     */
    public function getColumnOffset()
    {
        return $this->columnOffset;
    }


}