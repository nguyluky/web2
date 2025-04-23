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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $parent_id
 * @property array|null $require_fields Required fields configuration for this category
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
    public $incrementing = false;

    protected $casts = [
        'id' => 'int',
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
}
