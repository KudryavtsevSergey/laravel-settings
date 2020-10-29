<?php

namespace Sun\Settings\SettingStorages;

use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use stdClass;
use Sun\Locale\LocaleConfig;
use Sun\Settings\DTO\SettingDTO;
use Sun\Settings\SettingConfig;

class DBSettingStorage extends SettingStorage
{
    protected Builder $setting;

    public function __construct()
    {
        $this->setting = DB::table(SettingConfig::tableName());
    }

    public function retrieve(string $key): ?SettingDTO
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

        return SettingDTO::createFromData($setting);
    }

    public function store(string $key, $value = null, bool $locale = false): void
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

        $settings = $this->setting->select("{$tableName}.key", "{$tableName}.value", "{$relatedTableName}.value as locale_value")
            ->leftJoin($relatedTableName, function (JoinClause $join) use ($tableName, $relatedTableName) {
                $join->on("{$relatedTableName}.setting_key", '=', "{$tableName}.key")
                    ->where($relatedTableName . '.' . LocaleConfig::foreignColumnName(), '=', LocaleConfig::getLocale());
            })
            ->get();

        $settings->transform(function (stdClass $setting): array {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'locale_value' => $setting->locale_value,
            ];
        });

        return $this->encodeCollection($settings);
    }
}
