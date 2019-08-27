<?php

namespace Sun\Settings\SettingStorages;

use Illuminate\Support\Collection;

abstract class SettingStorage
{
    /**
     * @param string $key
     * @return array | null
     */
    public abstract function retrieve(string $key): ?array;

    /**
     * @param string $key
     * @param null $value
     * @param bool $locale
     * @return mixed
     */
    public abstract function store(string $key, $value = null, bool $locale = false);

    /**
     * @return Collection
     */
    public abstract function retrieveAll(): Collection;

    protected function encodeCollection(Collection $collection): Collection
    {
        return $collection->keyBy('key')->transform(function ($item) {
            $value = !empty($item['value']) ? json_decode($item['value'], true) : $item['value'];
            $localeValue = !empty($item['locale_value']) ? json_decode($item['locale_value'], true) : $item['locale_value'];

            return ['value' => $value, 'locale_value' => $localeValue];
        });
    }
}
