<?php

namespace Sun\Settings\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SettingLocale
 *
 * @property string $locale_code
 * @property string $value
 * @property string $setting_key
 *
 * @property Setting $setting
 *
 * @package App\Models\Models
 */
class SettingLocale extends Eloquent
{
    protected $table = 'setting_locale';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'value'
    ];

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'key', 'setting_key');
    }
}
