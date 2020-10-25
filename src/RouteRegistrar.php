<?php

namespace Sun\Settings;

use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Router;

class RouteRegistrar
{
    protected Registrar $router;

    public function __construct(Registrar $router)
    {
        $this->router = $router;
    }

    public function routes()
    {
        $this->router->group(['prefix' => 'settings', 'middleware' => ['web', 'auth']], function (Router $router) {
            $router->get('', [
                'uses' => 'SettingApiController@getAll',
                'as' => 'settings.list',
            ]);

            $router->get('/{key}', [
                'uses' => 'SettingApiController@get',
                'as' => 'settings.get',
            ]);

            $router->post('/{key}', [
                'uses' => 'SettingApiController@set',
                'as' => 'settings.set',
            ]);

            $router->post('/{key}/locale', [
                'uses' => 'SettingApiController@setLocale',
                'as' => 'settings.setLocale',
            ]);
        });
    }
}
