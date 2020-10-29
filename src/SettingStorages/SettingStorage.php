<?php

namespace Sun\Settings\SettingStorages;

use Illuminate\Support\Collection;
use Sun\Settings\DTO\SettingDTO;

abstract class SettingStorage
{
    public abstract function retrieve(string $key): ?SettingDTO;

    public abstract function store(string $key, $value = null, bool $locale = false): void;

    public abstract function retrieveAll(): Collection;

    protected function encodeCollection(Collection $collection): Collection
    {
        return $collection->keyBy('key')->transform(function (array $item): SettingDTO {
            return SettingDTO::createFromData($item);
        });
    }
}
