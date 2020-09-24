# Laravel settings package

## Installation

composer.json

```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/KudryavtsevSergey/laravel-settings.git"
        }
    ],
    "require": {
      "sun/settings": "dev-master"
    }
}
```

After updating composer, add the service provider to the ```providers``` array in ```config/app.php```

```php
[
    Sun\Settings\SettingServiceProvider::class,
];
```

And add alias:
```php
[
    'Setting' => Sun\Settings\Facade::class,
];
```

Then:

```shell script
php artisan migrate
```
