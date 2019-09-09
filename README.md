# Laravel settings package

## Installation

cd to project.

```shell script
mkdir -p packages/sun

cd packages/sun

git clone https://github.com/KudryavtsevSergey/laravel-settings.git settings
```

in your composer.json

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/sun/settings",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
      "sun/settings": "dev-master"
    }
}
```

After updating composer, add the service provider to the ```providers``` array in ```config/app.php```

```php
Sun\Settings\SettingServiceProvider::class,
```

And add alias:
```php
'Setting' => Sun\Settings\Facade::class,
```

Then:

```shell script
php artisan migrate
```
