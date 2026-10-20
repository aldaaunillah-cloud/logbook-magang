<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class NeonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Config::set(
            'database.connections.pgsql',
            Config::get('neon')
        );
    }

    public function boot(): void
    {
        //
    }
}