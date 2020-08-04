<?php

namespace MasterDmx\LaravelSnippets;

use Illuminate\Support\ServiceProvider;

class SnipetsServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }

    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/../config/snippets.php', 'snippets');

        $this->app->singleton(Snipets::class, function () {
            return new Snipets();
        });
    }
}
