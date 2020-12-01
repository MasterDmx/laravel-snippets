<?php

namespace MasterDmx\LaravelSnippets;

use Illuminate\Support\ServiceProvider;

class SnippetsServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }

    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/../config/snippets.php', 'snippets');

        $this->app->singleton(Snippets::class, function () {
            return new Snippets();
        });
    }
}
