<?php

namespace Jvelo\Datatext\Assets;

/**
 * Assets manager facade
 *
 * @package Jvelo\Datatext\Assets
 */
interface Assets {

    /**
     * @return Asset[] all registered assets against this instance of asset manager
     */
    public function all();

    /**
     * Requests an asset : ask for it to be loaded on the front-end
     *
     * @param Asset $asset the asset to be required
     */
    public function request(Asset $asset);

    /**
     * Convenience method to request a script simply by its location
     *
     * @param string $location the location of the script to be required by the front end
     */
    public function requestScript(string $location);

    /**
     * Convenience method to request a stylesheet simply by its location
     *
     * @param string $location the location of the script to be required by the front end
     */
    public function requestStylesheet(string $location);
}