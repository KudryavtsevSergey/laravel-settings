<?php

namespace Sun\Settings;

use Illuminate\Support\Collection;
use Sun\Settings\DTO\SettingWithDefaultDTO;
use Sun\Settings\SettingStorages\SettingStorage;

class Setting
{
    protected SettingStorage $storage;

    public function __construct(SettingStorage $storage)
    {
        $this->storage = $storage;
    }

    public function get(string $key, $defaultValue = null)
    {
        $setting = $this->getWithLocale($key);
        return $setting->getPrioritySetting($defaultValue);
    }

    public function getWithLocale(string $key): SettingWithDefaultDTO
    {
        $setting = $this->storage->retrieve($key);
        return new SettingWithDefaultDTO($setting, SettingConfig::defaultValueByKey($key));
    }

    public function setValue(string $key, $value = null): void
    {
        $this->store($key, $value);
    }

    public function setLocaleValue(string $key, $value = null): void
    {
        $this->store($key, $value, true);
    }

    private function store(string $key, $value = null, $locale = false): void
    {
        $this->storage->store($key, $value, $locale);
    }

    public function getAll(): Collection
    {
        $settings = $this->storage->retrieveAll();
        $defaultValues = SettingConfig::defaultValues();

        $result = collect();
        foreach ($defaultValues as $key => $defaultValue) {
            $setting = new SettingWithDefaultDTO($settings[$key] ?? null, $defaultValue);
            $result->put($key, $setting);
        }
        return $result;
    }

    public function getByKeys(array $keys): Collection
    {
        $settings = $this->getAll();

        return $settings->filter(function ($setting, $key) use ($keys): bool {
            return in_array($key, $keys);
        });
    }
}
