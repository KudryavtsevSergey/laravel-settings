<?php

namespace Sun\Settings;

use Illuminate\Support\ServiceProvider;
use Sun\Settings\SettingStorages\DBSettingStorage;
use Sun\Settings\SettingStorages\SettingStorage;
use Sun\Settings\SettingStorages\EloquentSettingStorage;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('settings.php')
        ], 'settings-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'settings');

        $this->app->singleton(Facade::FACADE, Setting::class);

        switch (config('settings.storage')) {
            case 'eloquent':
                $this->app->bind(SettingStorage::class, EloquentSettingStorage::class);
                break;
            case 'db':
                $this->app->bind(SettingStorage::class, DBSettingStorage::class);
                break;
        }
    }
}
