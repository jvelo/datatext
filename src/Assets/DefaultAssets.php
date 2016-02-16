<?php

namespace Jvelo\Datatext\Assets;

class DefaultAssets implements Assets {

    private $list = [];

    public function request(Asset $asset)
    {
        $list[]= $asset;
    }

    public function all()
    {
        return $this->list;
    }

    public function requestScript(string $location)
    {
        $this->request(new Asset(Asset::SCRIPT, $location));
    }

    public function requestStylesheet(string $location)
    {
        $this->request(new Asset(Asset::STYLESHEET, $location));
    }
}