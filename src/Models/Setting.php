<?php

namespace Sun\Settings\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Sun\Locale\Models\AbstractLocaleModel;
use Sun\Settings\SettingConfig;

/**
 * @property string $key
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method $this settingLocale()
 * @mixin Builder
 */
class Setting extends AbstractLocaleModel
{
    protected $primaryKey = 'key';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = SettingConfig::tableName();
    }

    protected $fillable = [
        'key',
        'value'
    ];

    public function scopeSettingLocale(Builder $query)
    {
        $this->joinName($query);
    }
}
