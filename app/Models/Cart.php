<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * 
 * @property int $profile_id
 * @property int $product_variant_id
 * @property int $amount
 * 
 * @property Product $product
 * @property Profile $profile
 *
 * @package App\Models
 */
class Cart extends Model
{
	protected $table = 'cart';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'profile_id' => 'int',
		'product_variant_id' => 'int',
		'amount' => 'int'
	];

	protected $fillable = [
		'profile_id', 'product_variant_id', 'amount'
	];

	public function productVariant()
	{
		return $this->belongsTo(ProductVariant::class, 'product_variant_id');
	}
	

	public function profile()
	{
		return $this->belongsTo(Profile::class);
	}
}
