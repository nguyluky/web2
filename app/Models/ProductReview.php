<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductReview
 * 
 * @property int $id
 * @property int $product_id
 * @property int|null $user_id
 * @property int $rating
 * @property string|null $comment
 * @property string|null $status
 * @property array|null $meta_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Account|null $account
 *
 * @package App\Models
 */
class ProductReview extends Model
{
	protected $table = 'product_reviews';

	protected $casts = [
		'product_id' => 'int',
		'user_id' => 'int',
		'rating' => 'int',
		'meta_data' => 'json'
	];

	protected $fillable = [
		'product_id',
		'user_id',
		'rating',
		'comment',
		'status',
		'meta_data'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function account()
	{
		return $this->belongsTo(Account::class, 'user_id');
	}
}
