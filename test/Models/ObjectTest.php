<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Models;

use Illuminate\Database\Capsule\Manager as DB;
use Jvelo\Datatext\Database\AbstractDatabaseTest;

/**
 * Class ObjectTest
 * @package Jvelo\Datatext\Models
 *
 * @group models
 */
class ObjectTest extends AbstractDatabaseTest
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

        $this->assertEquals(1, ObjectRevision::count());

        $fetched = Object::first();

        $this->assertEquals($fetched->data, '{}');

        $fetched->setJsonAttribute('metadata', 'modified', 'true');
        $fetched->save();

        $this->assertEquals(2, ObjectRevision::count());

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
