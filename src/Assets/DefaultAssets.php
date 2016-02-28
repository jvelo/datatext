<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Assets;

class DefaultAssets implements Assets {

    private $list = [];

    public function request(Asset $asset)
    {
        $this->list[]= $asset;
    }

    public function all()
    {
        return $this->list;
    }

    public function requestScript(string $location)
    {
        $this->request(new Asset(Asset::SCRIPT, $location));
    }

    public function requestStylesheet(string $location)
    {
        $this->request(new Asset(Asset::STYLESHEET, $location));
    }
}