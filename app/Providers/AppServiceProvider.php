<?php

namespace App\Providers;

use App\Repositories\AbstractRepository;
use App\Repositories\Eloquent\SitesDataRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(AbstractRepository::class, SitesDataRepository::class);
    }
}
