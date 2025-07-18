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
 * @property int $product_variant_id
 * @property int $serial
 *
 * @property Order $order
 * @property ProductVariant $product_variant
 * @property Collection|Warranty[] $warranties
 *
 * @package App\Models
 */
class OrderDetail extends Model
{
	protected $table = 'order_detail';
	protected $primaryKey = 'id';
    // public $incrementing = true;
    // protected $keyType = 'bigint';
	public $timestamps = false;

	protected $casts = [
		'order_id' => 'int',
		'product_variant_id' => 'int',
		'serial' => 'int'
	];

	protected $fillable = [
		'order_id',
		'product_variant_id',
		'serial'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function product_variant()
	{
		return $this->belongsTo(ProductVariant::class, 'product_variant_id');
	}

	public function warranties()
	{
		return $this->hasMany(Warranty::class, 'product_id');
	}
}
