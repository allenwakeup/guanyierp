<?php

/*
 *
 *
 * (c) Allen, Li <morningbuses@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Goodcatch\Guanyi\Laravel;

use Goodcatch\Guanyi\Guanyi;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Class ServiceProvider
 * @package Goodcatch\Guanyi\Laravel
 * @author Allen, Li
 */
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
        $this->offerPublishing();
        $this->registerServices();

    }

    /**
     * Register config.
     */
    protected function configure()
    {

        $this->mergeConfigFrom(
            __DIR__.'/../config/guanyi.php', 'guanyi'
        );


    }

    /**
     * Setup the resource publishing groups for Horizon.
     *
     * @return void
     */
    protected function offerPublishing()
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/guanyi.php' => config_path('guanyi.php'),
            ], 'guanyi-config');
        }
    }

    /**
     * Register Guanyi services in the container.
     *
     * @see link(http://gop.guanyierp.com/hc/kb/article/1235511/ Api_doc)
     *
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->bind('guanyierpapi', function ($app) {
            return new Guanyi ($app('config')['guanyi']);
        });
    }

}
