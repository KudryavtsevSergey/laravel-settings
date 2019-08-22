<?php


namespace Sun\Settings\Contracts;


interface SettingStorageContract
{
    /**
     * @param string $key
     * @return string | null
     */
    public function retrieve(string $key): ?array;

    /**
     * @param string $key
     * @param $value
     * @param $localeValue
     * @return mixed
     */
    public function store(string $key, $value, $localeValue);
}
