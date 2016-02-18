<?php

namespace Jvelo\Datatext\Api;

use Jvelo\Datatext\Models\Page;
use Jvelo\Datatext\Database\AbstractDatabaseTest;
use Jvelo\Datatext\Shortcodes\Tweet;
use Jvelo\Datatext\Support\Shortcodes;
use Jvelo\Datatext\Support\Assets;

/**
 * Class PagesTest
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

        $first = Assets::all()[0];
        $this->assertEquals('script', $first->getType());
        $this->assertEquals('//platform.twitter.com/widgets.js', $first->getLocation());

        $page = new Page;
        $page->content = '# datatext';
        $page->save();
    }

}