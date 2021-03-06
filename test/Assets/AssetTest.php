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
 * @package Jvelo\Datatext\Assets
 */
class AssetTest extends \PHPUnit_Framework_TestCase {

    public function testCreateValidAsset() {
        $asset = new Asset("script", "//platform.twitter.com/widgets.js");
        $this->assertNotNull($asset);
        $this->assertEquals([
            "type" => "script",
            "location" => "//platform.twitter.com/widgets.js",
            "options" => []
        ], $asset->jsonSerialize());


        $asset = new Asset("stylesheet", "//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css", [
            "media" => "screen"
        ]);
        $this->assertNotNull($asset);
        $this->assertEquals([
            "type" => "stylesheet",
            "location" => "//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css",
            "options" => [
                "media" => "screen"
            ]
        ], $asset->jsonSerialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidAssetWithInvalidType() {
        new Asset(-42, "fdsa");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidAssetWithNullType() {
        new Asset(null, "fdsa");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidAssetWithNullLocation() {
        new Asset("fdsa", null);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidAssetWithInvalidLocation() {
        new Asset(-42, null);
    }

}
