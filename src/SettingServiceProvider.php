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
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('settings.php')
        ], 'settings');
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
        $this->app->bind(SettingStorageContract::class, SettingStorage::class);
    }
}
