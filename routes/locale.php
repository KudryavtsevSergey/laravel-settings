<?php

use Illuminate\Routing\Router;

Route::group(['prefix' => 'setting', 'namespace' => '\Sun\Settings\Http\Controllers', 'middleware' => ['web']], function (Router $router) {
    $router->get('/{key}', [
        'uses' => 'SettingApiController@get',
        'as' => 'setting.get',
    ]);

    $router->post('/{key}', [
        'uses' => 'SettingApiController@set',
        'as' => 'setting.set',
    ]);

    $router->post('/{key}/locale', [
        'uses' => 'SettingApiController@setLocale',
        'as' => 'setting.setLocale',
    ]);
});
