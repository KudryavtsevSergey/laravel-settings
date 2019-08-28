<?php

namespace Sun\Settings;

use Sun\Locale\LocaleConfig;

class SettingConfig
{
    public static function tableName()
    {
        return config('settings.table');
    }

    public static function relatedTableName()
    {
        $tableName = static::tableName();
        $postfix = LocaleConfig::tablePostfix();

        return "{$tableName}{$postfix}";
    }
}
