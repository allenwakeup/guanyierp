<?php

/*
 *
 *
 * (c) Allen, Li <morningbuses@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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