<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Function
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|Rule[] $rules
 *
 * @package App\Models
 */
class Function_ extends Model
{
	protected $table = 'function';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'name'
	];

	public function rules()
	{
		return $this->belongsToMany(Rule::class, 'rule_function')
					->withPivot('name');
	}
}
