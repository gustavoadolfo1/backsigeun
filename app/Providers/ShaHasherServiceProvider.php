<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libraries\ShaHash\ShaHasher as ShaHasher;

class ShaHasherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('hash', function() {
            return new SHAHasher;
        });

    }
}
