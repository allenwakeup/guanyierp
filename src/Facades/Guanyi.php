<?php

namespace Goodcatch\Guanyi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Goodcatch\Guanyi\Guanyi
 * @author Allen, Li
 *
 */
class Guanyi extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'guanyierpapi';
    }
}