<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $status
 * @property int|null $parent_id
 * @property array|null $require_fields
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Category|null $category
 * @property Collection|Category[] $categories
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class Category extends Model
{
	protected $table = 'categories';

	protected $casts = [
		'parent_id' => 'int',
		'require_fields' => 'json'
	];

	protected $fillable = [
		'name',
		'slug',
		'status',
		'parent_id',
		'require_fields'
	];

	public function category()
	{
		return $this->belongsTo(Category::class, 'parent_id');
	}

	public function categories()
	{
		return $this->hasMany(Category::class, 'parent_id');
	}

	public function products()
	{
		return $this->hasMany(Product::class);
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
		if (isset($array['require_fields']) && is_string($array['require_fields'])) {
			$array['require_fields'] = json_decode($array['require_fields'], true);
		}
		
		return $array;
	}
}
