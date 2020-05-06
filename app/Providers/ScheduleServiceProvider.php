<?php

namespace App\Providers;

use App\Interfaces\ScheduleServiceInterface;
use App\Services\ScheduleService;

use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
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
        $this->app->bind(ScheduleServiceInterface::class, ScheduleService::class);
    }
}
