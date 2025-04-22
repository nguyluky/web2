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
 * @property int $account_id
 * @property string $status
 * @property Carbon $created_at
 * @property int $employee_id
 * @property string $payment_method
 * 
 * @property Account $account
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
		'account_id' => 'int',
		'employee_id' => 'int'
	];

	protected $fillable = [
		'account_id',
		'status',
		'employee_id',
		'payment_method'
	];

	public function account()
	{
		return $this->belongsTo(Account::class, 'employee_id');
	}

	public function order_details()
	{
		return $this->hasMany(OrderDetail::class);
	}
}
