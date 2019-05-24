<?php

namespace Sun\Settings\Models;

use App\Models\BaseLocaleModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Sun\Settings\Contracts\SettingStorageContract;

/**
 * Class Setting
 *
 * @property string $key
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method $this settingLocale()
 *
 * @package Sun\Settings\Models
 */
class Setting extends BaseLocaleModel implements SettingStorageContract
{
    protected $table = 'setting';
    protected $primaryKey = 'key';
    public $incrementing = false;

    protected $fillable = [
        'key',
        'value'
    ];

    public function scopeSettingLocale(Builder $query)
    {
        $this->joinName($query);
    }

    public function retrieve(string $key)
    {
        $setting = static::select('setting.key', 'setting.value', 'setting_locale.value as locale_value')
            ->where('key', $key)
            ->settingLocale()
            ->first();

        if (is_null($setting)) {
            return $setting;
        }

        $setting = $setting->toArray();

        $setting['value'] = !empty($setting['value']) ? json_decode($setting['value'], true) : $setting['value'];
        $setting['locale_value'] = !empty($setting['locale_value']) ? json_decode($setting['locale_value'], true) : $setting['locale_value'];

        return $setting;
    }

    public function store(string $key, $value, $localeValue)
    {
        $value = is_null($value) ? $value : json_encode($value);
        $localeValue = is_null($localeValue) ? $localeValue : json_encode($localeValue);

        $setting = static::updateOrCreate(['key' => $key], ['value' => $value]);

        if (!is_null($localeValue) && !empty($localeValue)) {
            $setting->replaceLocales(['value' => $localeValue]);
        } else {
            $setting->deleteLocales();

            if ((is_null($value) || empty($value)) && !$setting->existLocales()) {
                if ($setting->delete()) {
                    return null;
                }
            }
        }

        return $setting->toArray();
    }
}
