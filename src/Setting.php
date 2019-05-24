<?php


namespace Sun\Settings;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\App;
use Sun\Settings\Contracts\SettingStorageContract;

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

    protected function cacheHas(string $key)
    {
        return $this->cache->has($key);
    }

    protected function getFromCache(string $key)
    {
        return $this->cache->get($key);
    }

    protected function addToCache(string $key, $value)
    {
        $this->cache->add($key, $value, 1);
    }

    protected function getByKey(string $key)
    {
        /*if ($this->cacheHas($key)) {
            $setting = $this->getFromCache($key);
        } else {*/
        $setting = $this->storage->retrieve($key);

        /*    $this->addToCache($key, $setting);
        }*/
        return $setting;
    }

    public function get($key, $defaultValue)
    {
        $setting = $this->getWithLocale($key);

        return $setting['locale_value'] ?: $setting['value'] ?: $defaultValue;
    }

    public function getWithLocale($key)
    {
        $setting = $this->getByKey($key) ?: [
            'key' => $key,
            'value' => null,
            'locale_value' => null,
        ];

        return $setting;
    }

    public function setWithLocale($key, $value, $localeValue)
    {
        $setting = $this->storage->store($key, $value, $localeValue);
    }
}
