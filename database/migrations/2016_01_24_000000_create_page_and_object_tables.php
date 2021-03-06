<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePageAndObjectTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Pages

        DB::statement("CREATE TABLE page (
          id uuid,
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
          metadata_patch text NOT NULL,
          created_at timestamp without time zone NOT NULL,
          author text NOT NULL,
          PRIMARY KEY(page_id, revision_id)
        );");

        DB::statement("CREATE INDEX page_revision_author_index ON page_revision USING btree (author);");
        DB::statement("CREATE INDEX page_revision_date_index ON page_revision USING btree (created_at);");

        // Objects

        DB::statement("CREATE TABLE object (
          id uuid,
          data jsonb,
          metadata jsonb,
          type text,
          PRIMARY KEY(id)
        );");

        DB::statement("CREATE INDEX object_data_gin_index ON object USING GIN (data jsonb_path_ops);");
        DB::statement("CREATE INDEX object_type_index ON object USING BTREE (type)");
        DB::statement("CREATE INDEX object_metadata_created_at_index ON object (CAST (metadata->>'created_at' AS integer))");

        // Objects revisions

        DB::statement("CREATE TABLE object_revision (
          object_id uuid REFERENCES object(id),
          revision_id integer NOT NULL,
          data_patch text NOT NULL,
          metadata_patch text NOT NULL,
          created_at timestamp without time zone NOT NULL,
          author text NOT NULL,
          PRIMARY KEY(object_id, revision_id)
        );");

        DB::statement("CREATE INDEX object_revision_author_index ON object_revision USING btree (author);");
        DB::statement("CREATE INDEX object_revision_date_index ON object_revision USING btree (created_at);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('object_revision');
        Schema::dropIfExists('object');
        Schema::dropIfExists('page_revision');
        Schema::dropIfExists('page');
    }
}
