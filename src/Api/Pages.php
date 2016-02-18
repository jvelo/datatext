<?php

namespace Jvelo\Datatext\Api;

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