<?php

namespace Jvelo\Paidia\Models;

use Illuminate\Database\Capsule\Manager as DB;

class ObjectTest extends AbstractModelTest
{

    protected function tearDown()
    {
        $this->schema->dropIfExists('documents');
    }

    public function testCreateNewEmptyData()
    {
        $this->logger->info("testCreateNewEmptyData ...");

        $data = new Object;
        $data->type = 'blog-post';
        $data->save();

        $this->assertEquals(1, Object::count());

        $fetched = Object::first();

        $this->assertEquals($fetched->data, '{}');

        $data = new Object;
        $data->type = 'blog-post';
        $data->save();

        $this->assertEquals(2, Object::count());
    }

    public function testCreateNewDataWithContents()
    {
        $this->logger->info("testCreateNewDataWithContents ...");

        $data = new Object;
        $data->type = 'blog-post';

        $data->save();
        $data->setJsonAttribute('data', 'foo', 'bar');
        $data->save();

        $this->assertEquals(1, Object::count());

        $fetched = Object::first();

        $this->assertEquals($fetched->foo, 'bar');
    }

    public function testCreateNewDataWithContentsAndMetadata()
    {
        $this->logger->info("testCreateNewDataWithContentsAndMetadata ...");

        $data = new Object;
        $data->type = 'blog/post';

        $data->save();
        $data->setJsonAttribute('data', 'foo', 'bar');
        $data->setJsonAttribute('metadata', 'created_at', '1453631836');
        $data->save();

        $this->assertEquals(1, Object::count());

        $fetched = Object::first();

        $this->assertEquals($fetched->foo, 'bar');
    }

    /**
     * @expectedException \Illuminate\Database\QueryException
     * @expectedExceptionCode 22P02
     */
    public function testCreateNewDataWithContentsAndMetadataNotMatchingIndex()
    {
        $this->logger->info("testCreateNewDataWithContentsAndMetadataNotMatchingIndex ...");

        $data = new Object;
        $data->type = 'blog-post';

        $data->save();
        $data->setJsonAttribute('data', 'foo', 'bar');
        $data->setJsonAttribute('metadata', 'created_at', 'toto');
        $data->save();
    }
}