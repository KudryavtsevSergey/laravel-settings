<?php

namespace Sun\Settings\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Sun\Locale\Models\BaseModel;

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
}
