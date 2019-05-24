<?php


namespace Sun\Settings\Contracts;


interface SettingStorageContract
{
    //public static function all();

    /**
     * @param string $key
     * @return string | null
     */
    public function retrieve(string $key);

    public function store(string $key, $value, $localeValue);

    /*public function store($key, $value, $localeValue = null);

    public function remove($key, $locale = false);*/
}
