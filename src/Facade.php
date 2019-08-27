<?php

namespace Sun\Settings;

use Illuminate\Contracts\Routing\Registrar as Router;
use Route;
use Illuminate\Support\Facades\Facade as IlluminateFacade;

class Facade extends IlluminateFacade
{
    protected static function getFacadeAccessor()
    {
        return 'Setting';
    }
}
