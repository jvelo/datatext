<?php

namespace Jvelo\Datatext\Models;

use Illuminate\Database\Capsule\Manager as DB;

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

    public function testTablesHaveTableClass() {
        $page = new Page;
        $page->content =  <<< 'MARKDOWN'
|Tables|Is|
|------|--|
|OK    |? |
MARKDOWN;

        $this->assertEquals("<table>\n<thead>\n<tr>\n<th>Tables</th>\n<th>Is</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>OK</td>\n<td>?</td>\n</tr>\n</tbody>\n</table>", $page->toArray()['html_content']);
    }

}
