<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string|null $sku
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $category_id
 * @property float $base_price
 * @property float|null $base_original_price
 * @property string|null $status
 * @property array|null $specifications
 * @property array|null $features
 * @property array|null $meta_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Category|null $category
 * @property Collection|ImportDetail[] $import_details
 * @property Collection|ProductImage[] $product_images
 * @property Collection|ProductReview[] $product_reviews
 * @property Collection|ProductVariant[] $product_variants
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'product';
	protected $primaryKey = 'id';

	protected $casts = [
		'category_id' => 'int',
		'base_price' => 'float',
		'base_original_price' => 'float',
		'specifications' => 'array',
		'features' => 'array',
		'meta_data' => 'array'
	];

	protected $fillable = [
		'sku',
		'name',
		'slug',
		'description',
		'category_id',
		'base_price',
		'base_original_price',
		'status',
		'specifications',
		'features',
		'meta_data'
	];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function import_details()
	{
		return $this->hasMany(ImportDetail::class);
	}

	public function product_images()
	{
		return $this->hasMany(ProductImage::class);
	}

	public function product_reviews()
	{
		return $this->hasMany(ProductReview::class);
	}

	public function product_variants()
	{
		return $this->hasMany(ProductVariant::class);
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
		foreach (['specifications', 'features', 'meta_data'] as $field) {
			if (isset($array[$field]) && is_string($array[$field])) {
				$array[$field] = json_decode($array[$field], true);
			}
		}
		
		return $array;
	}
}
