<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductImage
 * 
 * @property int $id
 * @property int $product_id
 * @property int|null $variant_id
 * @property string $image_url
 * @property bool|null $is_primary
 * @property int|null $sort_order
 * @property Carbon|null $created_at
 * 
 * @property Product $product
 * @property ProductVariant|null $product_variant
 *
 * @package App\Models
 */
class ProductImage extends Model
{
	protected $table = 'product_images';
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
		'variant_id' => 'int',
		'is_primary' => 'bool',
		'sort_order' => 'int'
	];

	protected $fillable = [
		'product_id',
		'variant_id',
		'image_url',
		'is_primary',
		'sort_order'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function product_variant()
	{
		return $this->belongsTo(ProductVariant::class, 'variant_id');
	}
}
