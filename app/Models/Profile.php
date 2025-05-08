<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 * 
 * @property int $id
 * @property string|null $fullname
 * @property string $phone_number
 * @property string|null $email
 * @property string|null $avatar
 * 
 * @property Account $account
 * @property Collection|Address[] $addresses
 * @property Collection|Cart[] $carts
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Profile extends Model
{
	protected $table = 'profile';
	public $timestamps = false;

	protected $fillable = [
		'fullname',
		'phone_number',
		'email',
		'avatar'
	];

	public function account()
	{
		return $this->belongsTo(Account::class, 'id');
	}

	public function addresses()
	{
		return $this->hasMany(Address::class);
	}

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
