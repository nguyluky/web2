<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rule
 * 
 * @property int $id
 * @property string|null $name
 * @property int|null $status
 * 
 * @property Collection|Account[] $accounts
 * @property Collection|Function_[] $functions
 *
 * @package App\Models
 */
class Rule extends Model
{
	protected $table = 'rule';
	public $timestamps = false;

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'status'
	];

	public function accounts()
	{
		return $this->hasMany(Account::class, 'rule');
	}

	public function functions()
	{
		return $this->belongsToMany(Function_::class, 'rule_function')
					->withPivot('name');
	}
}
