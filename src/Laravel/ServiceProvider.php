<?php

/*
 *
 *
 * (c) Allen, Li <morningbuses@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Goodcatch\Laravel;

use Goodcatch\Guanyi\Guanyi;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;


class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

    }


    /**
     * Register any application services.
     */
    public function register()
    {
        $this->configure();
        $this->registerServices();

    }

    /**
     * Register config.
     */
    protected function configure()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/guanyi.php' => config_path('guanyi.php'),
            ], 'guanyi-config');
        }
    }

    /**
     * Register Horizon's services in the container.
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->singleton(Guanyi::class);
    }

}
