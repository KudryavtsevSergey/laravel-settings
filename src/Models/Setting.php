<?php

namespace Sun\Settings\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Sun\Locale\Models\BaseModel;
use Sun\Settings\SettingConfig;

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
class Setting extends BaseModel
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
