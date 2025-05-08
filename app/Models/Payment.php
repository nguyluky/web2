<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $id
 * @property string $description
 * 
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payment';
	public $timestamps = false;

	protected $fillable = [
		'description'
	];

	public function orders()
	{
		return $this->hasMany(Order::class, 'payment_method');
	}
}
