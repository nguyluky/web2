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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property array $attributes
 * 
 * @property Product $product
 * @property Collection|ProductImage[] $product_images
 *
 * @package App\Models
 */
class ProductVariant extends Model
{
	protected $table = 'product_variants';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'product_id' => 'int',
		'price' => 'float',
		'original_price' => 'float',
		'stock' => 'int',
		'attributes' => 'json'
	];

	protected $fillable = [
		'product_id',
		'sku',
		'price',
		'original_price',
		'stock',
		'status',
		'attributes'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function product_images()
	{
		return $this->hasMany(ProductImage::class, 'variant_id');
	}
}
