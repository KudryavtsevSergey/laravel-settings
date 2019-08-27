<?php

namespace Sun\Settings;

use Illuminate\Support\ServiceProvider;
use Sun\Settings\SettingStorages\DBSettingStorage;
use Sun\Settings\SettingStorages\SettingStorageContract;
use Sun\Settings\SettingStorages\EloquentSettingStorage;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('settings.php')
        ], 'settings-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/locale.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'settings');

        $this->app->singleton('Setting', Setting::class);

        switch (config('settings.storage')) {
            case 'eloquent':
                $this->app->bind(SettingStorageContract::class, EloquentSettingStorage::class);
                break;
            case 'db':
                $this->app->bind(SettingStorageContract::class, DBSettingStorage::class);
                break;
        }
    }
}
