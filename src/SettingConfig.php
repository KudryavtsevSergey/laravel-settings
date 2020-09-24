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
}
