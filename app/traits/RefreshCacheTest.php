<?php

namespace App\traits;
use Illuminate\Support\Facades\Artisan;

trait RefreshCacheTest
{
    public function RefreshCacheTest()
    {
        Artisan::call('config:clear');

        Artisan::call('config:cache');

        Artisan::call('config:clear --env=testing');

        Artisan::call('config:cache --env=testing');
    }
}