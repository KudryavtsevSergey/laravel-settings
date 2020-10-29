<?php

namespace Sun\Settings\SettingStorages;

use Illuminate\Support\Collection;
use Sun\Settings\DTO\SettingDTO;
use Sun\Settings\Models\Setting;
use Sun\Settings\SettingConfig;

class EloquentSettingStorage extends SettingStorage
{
    protected Setting $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function retrieve(string $key): ?SettingDTO
    {
        $tableName = SettingConfig::tableName();
        $relatedTableName = SettingConfig::relatedTableName();

        $setting = $this->setting->select("{$tableName}.key", "{$tableName}.value", "{$relatedTableName}.value as locale_value")
            ->settingLocale()
            ->find($key);

        if (is_null($setting)) {
            return null;
        }

        $setting = $setting->toArray();

        return SettingDTO::createFromData($setting);
    }

    public function store(string $key, $value = null, bool $locale = false): void
    {
        $value = is_null($value) ? $value : json_encode($value);

        if ($locale) {
            $setting = $this->setting->firstOrCreate(['key' => $key]);
            $setting->replaceLocales(['value' => $value]);
        } else {
            $this->setting->updateOrCreate(['key' => $key], ['key' => $key, 'value' => $value]);
        }
    }

    public function retrieveAll(): Collection
    {
        $tableName = SettingConfig::tableName();
        $relatedTableName = SettingConfig::relatedTableName();

        $settings = $this->setting->select("{$tableName}.key", "{$tableName}.value", "{$relatedTableName}.value as locale_value")
            ->settingLocale()
            ->get();

        $settings->transform(function (Setting $setting) {
            return $setting->toArray();
        });

        return $this->encodeCollection($settings);
    }
}
