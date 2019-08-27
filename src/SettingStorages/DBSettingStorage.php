<?php

namespace Sun\Settings\SettingStorages;

use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Sun\Locale\Locale;

class DBSettingStorage implements SettingStorageContract
{
    /**
     * @var Builder
     */
    protected $setting;

    /**
     * @var Locale
     */
    protected $locale;

    public function __construct(Locale $locale)
    {
        $this->setting = DB::table('setting');
        $this->locale = $locale;
    }

    public function retrieve(string $key): ?array
    {
        $relatedTableName = "setting{$this->locale->tablePostfix()}";

        $setting = $this->setting->select('setting.value', "{$relatedTableName}.value as locale_value")
            ->join($relatedTableName, function (JoinClause $join) use ($relatedTableName) {
                $join->on("{$relatedTableName}.setting_key", '=', "setting.key")
                    ->where("{$relatedTableName}.{$this->locale->foreignColumnName()}", '=', $this->locale->getLocale());
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

            $relatedTableName = "setting{$this->locale->tablePostfix()}";

            DB::table($relatedTableName)->updateOrInsert([
                $this->locale->foreignColumnName() => $this->locale->getLocale(),
                'setting_key' => $key,
            ], ['value' => $value]);
        } else {
            $this->setting->updateOrInsert(['key' => $key], ['value' => $value]);
        }
    }

    public function retrieveAll(): Collection
    {
        // TODO: Implement retrieveAll() method.
    }
}
