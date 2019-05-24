<?php

namespace Sun\Settings;

use Illuminate\Support\ServiceProvider;
use Sun\Settings\Contracts\SettingStorageContract;
use Sun\Settings\Models\Setting as SettingStorage;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('settings.php')
        ], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Setting', Setting::class);
        $this->app->bind(SettingStorageContract::class, SettingStorage::class);
    }
}
