<?php

namespace Jvelo\Datatext\Models;

use Illuminate\Database\Capsule\Manager as DB;
use Jvelo\Datatext\Support\Shortcodes;
use Jvelo\Datatext\Shortcodes\Identity;

class PageTest extends AbstractModelTest {

    public function testCreatePage() {
        $page = new Page;

        $page->content = "My content";
        $page->setJsonAttribute('metadata', 'title', "My page title");

        $page->save();

        $this->assertEquals(1, Page::count());
        $fetchedPage = Page::first();
        $this->assertEquals("my-page-title", $fetchedPage->slug);

        $this->assertEquals(1, Revision::count());
        $fetchedRevision = Revision::first();

        $array = $fetchedRevision->attributesToArray();
        $this->logger->info("Attributes", $array);
        $this->logger->info("page_id", [ $fetchedRevision->page_id ]);

        $this->assertEquals($fetchedRevision->page_id, $fetchedPage->id, "Page id is preserved");
    }

    public function testCreateAndUpdatePage() {
        $page = new Page;

        $page->content = "My content";
        $page->setJsonAttribute('metadata', 'title', "My page title");
        $page->setJsonAttribute('metadata', 'tags', ['foo', 'bar']);

        $page->save();

        $page->content = "I've updated my content";
        $page->setJsonAttribute('metadata', 'title', "My page title has changed also");
        $page->setJsonAttribute('metadata', 'tags', ['bar', 'fizz']);
        $page->save();

        $this->assertEquals(1, Page::count());
        $fetchedPage = Page::first();
        $this->assertEquals("my-page-title", $fetchedPage->slug);

        $this->assertEquals(2, Revision::count());
        $fetchedRevision = Revision::orderBy('revision_id', 'DESC')->first();

        $array = $fetchedRevision->attributesToArray();
        $this->logger->info("Attributes", $array);
        $this->logger->info("page_id", [ $fetchedRevision->page_id ]);

        $this->assertEquals($fetchedRevision->page_id, $fetchedPage->id, "Page id is preserved");
    }

    public function testSimpleMarkdownConversion() {
        $page = new Page;
        $page->content = "## Hello";
        $this->assertEquals("<h2>Hello</h2>", $page->toArray()['html_content']);
    }

    public function testMarkdownIsRendered() {
        $page = new Page;
        $page->content =  <<< 'MARKDOWN'
# Yes
MARKDOWN;

        $this->assertEquals("<h1>Yes</h1>", $page->toArray()['html_content']);
    }

    public function testShortCodesAreRendered() {
        Shortcodes::register(new Identity);
        $page = new Page;
        $page->content =  <<< 'MARKDOWN'
[identity]hello[/identity]
MARKDOWN;

        $this->assertEquals("<p>hello</p>", $page->toArray()['html_content']);
    }

}
