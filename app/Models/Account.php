<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * 
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int|null $rule
 * @property string $status
 * @property Carbon $created
 * @property Carbon $updated
 * 
 * @property Collection|Order[] $orders
 * @property Collection|ProductReview[] $product_reviews
 * @property Profile|null $profile
 *
 * @package App\Models
 */
class Account extends Model
{
	protected $table = 'account';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'rule' => 'int',
		'created' => 'datetime',
		'updated' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'password',
		'rule',
		'status',
		'created',
		'updated'
	];

	public function rule()
	{
		return $this->belongsTo(Rule::class, 'rule');
	}

	public function orders()
	{
		return $this->hasMany(Order::class, 'employee_id');
	}

	public function product_reviews()
	{
		return $this->hasMany(ProductReview::class, 'user_id');
	}

	public function profile()
	{
		return $this->hasOne(Profile::class, 'id');
	}
}
