<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Api;

use Jvelo\Datatext\Support\Api\Pages;
use Jvelo\Datatext\Models\Page;
use Jvelo\Datatext\Database\AbstractDatabaseTest;
use Jvelo\Datatext\Shortcodes\Tweet;
use Jvelo\Datatext\Support\Shortcodes;
use Jvelo\Datatext\Support\Assets;

/**
 * Test for pages API
 *
 * @package Jvelo\Datatext\Api
 *
 * @group api
 */
class PagesTest extends AbstractDatabaseTest {

    public function testCreateAndGetPageWithAssets() {
        Shortcodes::register(new Tweet());

        $page = new Page;
        $page->content = "[tweet id=\"20\"]";
        $page->save();

        $this->assertContains("twttr.widgets.createTweet", $page->html_content);

        $this->assertEquals(1, count(Assets::all()));

        $asset = Assets::all()[0];
        $this->assertEquals('script', $asset->getType());
        $this->assertEquals('//platform.twitter.com/widgets.js', $asset->getLocation());

        $page = new Page;
        $page->content = '# datatext';
        $page->save();

        $fetched = Pages::getPage($page->id);
        $this->assertEquals('<h1>datatext</h1>', $fetched['page']['html_content']);
    }

}