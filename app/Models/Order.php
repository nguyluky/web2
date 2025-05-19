<?php

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
 * @property string $payment_method
 * @property int $address_id
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
    // public $incrementing = true;
    // protected $keyType = 'bigint';
	public $timestamps = false;

	protected $casts = [
		'profile_id' => 'int',
		'payment_method' => 'string',
        'address_id' => 'int'
	];

	protected $fillable = [
		'profile_id',
		'status',
		'payment_method',
        'address_id'
	];

	// public function payment()
	// {
	// 	return $this->belongsTo(Payment::class, 'payment_method');
	// }

    public function profile()
    {
        // Liên kết qua account
        // return $this->hasOneThrough(Profile::class, Account::class, 'id', 'id', 'account_id', 'id');
		return $this->belongsTo(Profile::class, 'profile_id');
    }

	public function order_details()
	{
		return $this->hasMany(OrderDetail::class);
	}

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    
}