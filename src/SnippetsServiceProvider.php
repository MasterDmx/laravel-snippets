<?php

namespace MasterDmx\LaravelSnippets;

use Illuminate\Support\ServiceProvider;

class SnippetsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/snippets.php' => config_path('snippets.php'),
        ], 'config');

        /** @var Snippets $snippets */
        $snippets = $this->app->get(Snippets::class);

        foreach (config('snippets.presets', []) as $name => $classes){
            if (is_array($classes)){
                $snippets->addPreset($name, $classes);
            }
        }
    }

    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/../config/snippets.php', 'snippets');

        $this->app->singleton(Snippets::class);
    }
}
