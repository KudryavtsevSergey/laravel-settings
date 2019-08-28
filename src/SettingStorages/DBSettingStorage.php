<?php

namespace Sun\Settings\SettingStorages;

use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Sun\Locale\LocaleConfig;
use Sun\Settings\SettingConfig;

class DBSettingStorage extends SettingStorage
{
    /**
     * @var Builder
     */
    protected $setting;

    public function __construct()
    {
        $this->setting = DB::table(SettingConfig::tableName());
    }

    public function retrieve(string $key): ?array
    {
        $tableName = SettingConfig::tableName();
        $relatedTableName = SettingConfig::relatedTableName();

        $setting = $this->setting->select("{$tableName}.value", "{$relatedTableName}.value as locale_value")
            ->leftJoin($relatedTableName, function (JoinClause $join) use ($tableName, $relatedTableName) {
                $join->on("{$relatedTableName}.setting_key", '=', "{$tableName}.key")
                    ->where($relatedTableName . '.' . LocaleConfig::foreignColumnName(), '=', LocaleConfig::getLocale());
            })
            ->where('key', '=', $key)
            ->first();

        if (is_null($setting)) {
            return null;
        }

        $setting = (array)$setting;

        $value = !empty($setting['value']) ? json_decode($setting['value'], true) : null;
        $localeValue = !empty($setting['locale_value']) ? json_decode($setting['locale_value'], true) : null;

        return ['value' => $value, 'locale_value' => $localeValue];
    }

    public function store(string $key, $value = null, bool $locale = false)
    {
        $value = is_null($value) ? $value : json_encode($value);

        if ($locale) {
            $this->setting->updateOrInsert(['key' => $key]);

            $relatedTableName = SettingConfig::relatedTableName();

            DB::table($relatedTableName)->updateOrInsert([
                LocaleConfig::foreignColumnName() => LocaleConfig::getLocale(),
                'setting_key' => $key,
            ], ['value' => $value]);
        } else {
            $this->setting->updateOrInsert(['key' => $key], ['value' => $value]);
        }
    }

    public function retrieveAll(): Collection
    {
        $tableName = SettingConfig::tableName();
        $relatedTableName = SettingConfig::relatedTableName();

        $settings = $this->setting->select("{$tableName}.value", "{$relatedTableName}.value as locale_value")
            ->leftJoin($relatedTableName, function (JoinClause $join) use ($tableName, $relatedTableName) {
                $join->on("{$relatedTableName}.setting_key", '=', "{$tableName}.key")
                    ->where($relatedTableName . '.' . LocaleConfig::foreignColumnName(), '=', LocaleConfig::getLocale());
            })
            ->get();

        return $this->encodeCollection($settings);
    }
}
