<?php

namespace Sun\Settings\SettingStorages;

use Illuminate\Support\Collection;
use Sun\Settings\Models\Setting;

class EloquentSettingStorage extends SettingStorage
{
    /**
     * @var Setting
     */
    protected $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function retrieve(string $key): ?array
    {
        $setting = $this->setting->select('setting.key', 'setting.value', 'setting_locale.value as locale_value')
            ->settingLocale()
            ->find($key);

        if (is_null($setting)) {
            return null;
        }

        $setting = $setting->toArray();

        $value = !empty($setting['value']) ? json_decode($setting['value'], true) : null;
        $localeValue = !empty($setting['locale_value']) ? json_decode($setting['locale_value'], true) : null;

        return ['value' => $value, 'locale_value' => $localeValue];
    }

    public function store(string $key, $value = null, bool $locale = false)
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
        $settings = $this->setting->select('setting.key', 'setting.value', 'setting_locale.value as locale_value')
            ->settingLocale()
            ->get();

        return $this->encodeCollection($settings);
    }
}