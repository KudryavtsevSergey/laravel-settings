<?php

namespace Sun\Settings;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Collection;
use Sun\Settings\SettingStorages\SettingStorageContract;

/**
 * Class Setting
 * @package Sun\Settings
 */
class Setting
{
    /**
     * @var SettingStorageContract
     */
    protected $storage;
    /**
     * @var Repository
     */
    protected $cache;

    public function __construct(SettingStorageContract $storage, Repository $cache)
    {
        $this->storage = $storage;
        $this->cache = $cache;
    }

    private function cacheHas(string $key): bool
    {
        return $this->cache->has($key);
    }

    private function getFromCache(string $key): array
    {
        return $this->cache->get($key);
    }

    private function deleteFromCache(string $key)
    {
        $this->cache->delete($key);
    }

    private function addToCache(string $key, $value)
    {
        $this->cache->add($key, $value, config('settings.cache_time'));
    }

    private function replaceInCache(string $key, $value = null)
    {
        if (is_null($value)) {
            $this->deleteFromCache($key);
        } else {
            $this->addToCache($key, $value);
        }
    }

    private function getByKey(string $key): ?array
    {
        $cacheKey = $this->getCacheDetailKey($key);

        if ($this->cacheHas($cacheKey)) {
            $setting = $this->getFromCache($cacheKey);
        } else {
            $setting = $this->storage->retrieve($key);

            $this->addToCache($cacheKey, $setting);
        }
        return $setting;
    }

    private function getCacheDetailKey(string $key): string
    {
        return "detail_{$key}";
    }

    private function getCacheListKey(): string
    {
        return "all_settings_unique_cache_key";
    }

    public function get($key, $defaultValue = null)
    {
        $defaultValue = $defaultValue ?? config("settings.default_values.{$key}");

        $setting = $this->getWithLocale($key);

        return $setting['locale_value'] ?: $setting['value'] ?: $defaultValue;
    }

    public function getWithLocale($key): array
    {
        $setting = $this->getByKey($key) ?? [
                'key' => $key,
                'value' => null,
                'locale_value' => null,
            ];

        return $setting;
    }

    public function setValue(string $key, $value = null)
    {
        $this->set($key, $value);
    }

    public function setLocaleValue(string $key, $value = null)
    {
        $this->set($key, $value, true);
    }

    public function set(string $key, $value = null, $locale = false)
    {
        $setting = $this->storage->store($key, $value, $locale);

        $cacheKey = $this->getCacheDetailKey($key);
        $this->deleteFromCache($cacheKey);
        $this->deleteFromCache($this->getCacheListKey());
    }

    public function getAll(): Collection
    {
        $cacheKey = $this->getCacheListKey();

        if ($this->cacheHas($cacheKey)) {
            $settings = $this->getFromCache($cacheKey);
        } else {
            $settings = $this->storage->retrieveAll();

            $settings = $this->getCollectionValues($settings);

            $this->addToCache($cacheKey, $settings);
        }

        return $settings;
    }

    public function getByKeys(array $keys): Collection
    {
        $settings = $this->getAll();

        $flippedKeys = collect($keys)->flip();

        $flippedKeys->transform(function () {
            return null;
        });

        $filteredSettings = $settings->filter(function ($setting, $key) use ($keys) {
            return in_array($key, $keys);
        });

        return $flippedKeys->merge($filteredSettings);
    }

    private function getCollectionValues(Collection $items): Collection
    {
        $defaultValues = config('settings.default_values');

        return $items->map(function (array $setting, $key) use ($defaultValues) {
            return $setting['locale_value'] ?: $setting['value'] ?: $defaultValues[$key];
        });
    }
}
