<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Assets;

/**
 * Asset value object. Represents an asset that can be "required" (a.k.a requested) to be included by the front-end.
 *
 * @package Jvelo\Datatext\Assets
 */
class Asset implements \JsonSerializable {

    private $type;
    private $location;
    private $options;

    const SCRIPT = 'script';
    const STYLESHEET = 'stylesheet';

    /**
     * @param string $type the type of asset. For example 'script' or 'stylesheet'
     * @param string $location the location where to find the asset. Can be an absolute or relative path or an external URL
     * @param array $options the options for this asset. Typically attributes of the targeted tag : "rel", "type", etc.
     *
     * @throws \InvalidArgumentException type or location are missing or invalid
     */
    function __construct($type, $location, $options = []) {

        if (!is_string($type)) {
            throw new \InvalidArgumentException('Invalid asset type');
        }

        if (!is_string($location)) {
            throw new \InvalidArgumentException('Invalid asset location');
        }

        $this->type = $type;
        $this->location = $location;
        $this->options = $options;
    }

    /**
     * @return string the type of asset
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string the location of the asset
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'location' => $this->location,
            'options' => $this->options
        ];
    }
}