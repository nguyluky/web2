<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @property int $id
 * @property int $profile_id
 * @property string $status
 * @property Carbon $created_at
 * @property int $payment_method
 *
 * @property Profile $profile
 * @property Collection|OrderDetail[] $order_details
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'order';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'profile_id' => 'int',
	];

	protected $fillable = [
		'profile_id',
		'status',
		'payment_method'
	];


	public function order_details()
	{
		return $this->hasMany(OrderDetail::class);
	}
}
