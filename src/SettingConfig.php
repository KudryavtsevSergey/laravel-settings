<?php

namespace Sun\Settings;

use Sun\Locale\LocaleConfig;

class SettingConfig
{
    public static function tableName(): string
    {
        return config('settings.table');
    }

    public static function relatedTableName(): string
    {
        $tableName = static::tableName();
        $postfix = LocaleConfig::tablePostfix();
        return sprintf('%s%s', $tableName, $postfix);
    }

    public static function defaultValueByKey(string $key)
    {
        $settingKey = sprintf('settings.default_values.%s', $key);
        return config($settingKey);
    }

    public static function defaultValues(): array
    {
        return config('settings.default_values', []);
    }
}
