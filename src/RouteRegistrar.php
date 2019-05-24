<?php

namespace Sun\Settings;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * @var Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function apiRoutes()
    {
        $this->router->get('/{key}', [
            'uses' => 'SettingApiController@get',
            'as' => 'setting.get',
        ]);

        //TODO: post
        $this->router->post('/{key}', [
            'uses' => 'SettingApiController@set',
            'as' => 'setting.set',
        ]);
    }
}
