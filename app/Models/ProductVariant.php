<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductVariant
 * 
 * @property int $id
 * @property int $product_id
 * @property string|null $sku
 * @property float $price
 * @property float|null $original_price
 * @property int|null $stock
 * @property string|null $status
 * @property array $specifications
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Collection|Cart[] $carts
 * @property Collection|OrderDetail[] $order_details
 * @property Collection|ProductImage[] $product_images
 *
 * @package App\Models
 */
class ProductVariant extends Model
{
	protected $table = 'product_variants';
	protected $primaryKey = 'id';

	protected $casts = [
		'product_id' => 'int',
		'price' => 'float',
		'original_price' => 'float',
		'stock' => 'int',
		'specifications' => 'json'
	];

	protected $fillable = [
		'product_id',
		'sku',
		'price',
		'original_price',
		'stock',
		'status',
		'specifications',
		'attributes'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}

	public function order_details()
	{
		return $this->hasMany(OrderDetail::class);
	}

	public function product_images()
	{
		return $this->hasMany(ProductImage::class, 'variant_id');
	}
	
	// Ensure JSON fields are properly pre-processed before JSON serialization
	protected function serializeDate($date)
	{
		return $date->format('Y-m-d H:i:s');
	}

	// Override toArray to ensure JSON fields are properly decoded
	public function toArray()
	{
		$array = parent::toArray();
		
		// Make sure JSON fields are properly decoded when serializing
		if (isset($array['specifications']) && is_string($array['specifications'])) {
			$array['specifications'] = json_decode($array['specifications'], true);
		}
		
		return $array;
	}
}
