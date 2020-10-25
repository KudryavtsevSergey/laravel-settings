<?php

namespace Sun\Settings;

use Illuminate\Contracts\Routing\Registrar as Router;
use Route;
use Illuminate\Support\Facades\Facade as IlluminateFacade;

class Facade extends IlluminateFacade
{
    const FACADE = 'Setting';

    protected static function getFacadeAccessor()
    {
        return self::FACADE;
    }
}
