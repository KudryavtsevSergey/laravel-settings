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

    /**
     * @param array $options
     * @return void
     */
    public static function routes(array $options = [])
    {
        $defaultOptions = ['prefix' => 'setting', 'namespace' => '\Sun\Settings\Http\Controllers'];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function (Router $router) {
            (new RouteRegistrar($router))->apiRoutes();
        });
    }
}
