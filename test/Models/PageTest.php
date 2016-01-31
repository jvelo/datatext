<?php

namespace Jvelo\Paidia\Models;

use Illuminate\Database\Capsule\Manager as DB;

class PageTest extends AbstractModelTest {

    public function testCreatePage() {
        $page = new Page;

        $page->title = "My page title";
        $page->content = "My content";

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

        $page->title = "My page title";
        $page->content = "My content";

        $page->save();

        $page->content = "I've updated my content";
        $page->title = "My page title has changed also";
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

}