<?php

namespace Sun\Settings;

use \Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Setting
 *
 * @property int $id
 * @property string $key
 * @property string $locale_code
 * @property string $value
 *
 * @package App\Models
 */
class Setting extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'key',
		'locale_code',
		'value'
	];
}
