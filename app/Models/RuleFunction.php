<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RuleFunction
 * 
 * @property int $rule_id
 * @property int $function_id
 * @property string|null $name
 * 
 * @property Function_ $function
 * @property Rule $rule
 *
 * @package App\Models
 */
class RuleFunction extends Model
{
	protected $table = 'rule_function';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'rule_id' => 'int',
		'function_id' => 'int'
	];

	protected $fillable = [
		'name'
	];

	public function function()
	{
		return $this->belongsTo(Function_::class);
	}

	public function rule()
	{
		return $this->belongsTo(Rule::class);
	}
}
