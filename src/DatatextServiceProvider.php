<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class DatatextServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        Log::info("Registering datatext service provider");
    }

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        Log::info("Booting Datatext service");

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        App::bind("datatext.user_provider", '\Jvelo\Datatext\LaravelUserProvider');
        App::bind("datatext.assets_manager", '\Jvelo\Datatext\Assets\DefaultAssets');
        App::bind("datatext.shortcodes", '\Jvelo\Datatext\Shortcodes\Shortcodes');
        App::bind("datatext.api.pages", '\Jvelo\Datatext\Api\Pages');
    }
}
