<?php

namespace Jvelo\Paidia\Models;

use Illuminate\Database\Capsule\Manager as DB;

class PageTest extends AbstractModelTest {

    protected function setUpDatabase()
    {
        $this->schema->dropIfExists('page_revision');
        $this->schema->dropIfExists('page');

        DB::statement("CREATE TABLE page (
          id uuid,
          title text NOT NULL,
          slug text NOT NULL,
          content text NOT NULL,
          metadata jsonb NOT NULL,
          PRIMARY KEY(id)
        );");

        DB::statement("CREATE INDEX page_metadata_index ON page USING GIN (metadata jsonb_path_ops);");

        // Pages revisions

        DB::statement("CREATE TABLE page_revision (
          page_id uuid REFERENCES page(id),
          revision_id integer NOT NULL,
          content_patch text NOT NULL,
          title_patch text NOT NULL,
          created_at timestamp without time zone NOT NULL,
          author text NOT NULL,
          PRIMARY KEY(page_id, revision_id)
        );");

        DB::statement("CREATE INDEX page_revision_author_index ON page_revision USING btree (author);");
        DB::statement("CREATE INDEX page_revision_date_index ON page_revision USING btree (created_at);");
    }


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