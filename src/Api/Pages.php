<?php

namespace Jvelo\Datatext\Api;

use Jvelo\Datatext\Models\Page;
use Jvelo\Datatext\Support\Assets;

class Pages {

    public function getPage($id) {
        $page = Page::findOrFail($id);
        $assets = Assets::all();
        return [
            "page" => $page,
            "assets" => $assets
        ];
    }

}