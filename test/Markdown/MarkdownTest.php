<?php

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
