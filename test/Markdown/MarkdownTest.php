<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Markdown;

class MarkdownTest extends \PHPUnit_Framework_TestCase {

    public function testTablesHaveTableClass() {
        $parser = new Markdown;
        $content =  <<< 'MARKDOWN'
|Tables|Is|
|------|--|
|OK    |? |
MARKDOWN;
        $result = $parser->text($content);
        $this->assertEquals("<table class=\"table\">\n<thead>\n<tr>\n<th>Tables</th>\n<th>Is</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>OK</td>\n<td>?</td>\n</tr>\n</tbody>\n</table>", $result);
    }

}
