<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderDetail
 * 
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $serial
 * 
 * @property Order $order
 * @property Product $product
 * @property Collection|Warranty[] $warranties
 *
 * @package App\Models
 */
class OrderDetail extends Model
{
	protected $table = 'order_detail';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'order_id' => 'int',
		'product_id' => 'int',
		'serial' => 'int'
	];

	protected $fillable = [
		'order_id',
		'product_id',
		'serial'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function warranties()
	{
		return $this->hasMany(Warranty::class, 'product_id');
	}
}
