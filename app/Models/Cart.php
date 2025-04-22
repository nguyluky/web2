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
 * @property int $product_id
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
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'profile_id' => 'int',
		'product_id' => 'int',
		'amount' => 'int'
	];

	protected $fillable = [
		'amount'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function profile()
	{
		return $this->belongsTo(Profile::class);
	}
}
