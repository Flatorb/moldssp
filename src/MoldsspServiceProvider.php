<?php

namespace Flatorb\Moldssp;

use Illuminate\Support\ServiceProvider;

class MoldsspServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Moldssp::class, function() {
            return new Moldssp();
        });
    }
}
