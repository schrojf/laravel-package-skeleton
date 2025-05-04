<?php

namespace Vendor\PackageName\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vendor\PackageName\PackageName
 */
class PackageName extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Vendor\PackageName\PackageName::class;
    }
}
