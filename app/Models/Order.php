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
 * @property Payment $payment
 * @property Profile $profile
 * @property Collection|OrderDetail[] $order_details
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'order';
    public $incrementing = false;
    protected $keyType = 'int';
	public $timestamps = false;

	protected $casts = [
		'profile_id' => 'int',
		'payment_method' => 'int'
	];

	protected $fillable = [
		'profile_id',
		'status',
		'payment_method'
	];

	public function payment()
	{
		return $this->belongsTo(Payment::class, 'payment_method');
	}

	public function profile()
	{
		return $this->belongsTo(Profile::class);
	}

	public function order_details()
	{
		return $this->hasMany(OrderDetail::class);
	}
}
