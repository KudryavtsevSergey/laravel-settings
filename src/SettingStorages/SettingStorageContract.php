<?php

namespace Sun\Settings\SettingStorages;

use Illuminate\Support\Collection;

interface SettingStorageContract
{
    /**
     * @param string $key
     * @return array | null
     */
    public function retrieve(string $key): ?array;

    /**
     * @param string $key
     * @param null $value
     * @param bool $locale
     * @return mixed
     */
    public function store(string $key, $value = null, bool $locale = false);

    /**
     * @return Collection
     */
    public function retrieveAll(): Collection;
}
