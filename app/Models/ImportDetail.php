<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ImportDetail
 * 
 * @property int $id
 * @property int $import_id
 * @property int $product_id
 * @property int $import_price
 * @property int $amount
 * 
 * @property Import $import
 * @property Product $product
 *
 * @package App\Models
 */
class ImportDetail extends Model
{
	protected $table = 'import_detail';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'import_id' => 'int',
		'product_id' => 'int',
		'import_price' => 'int',
		'amount' => 'int'
	];

	protected $fillable = [
		'import_id',
		'product_id',
		'import_price',
		'amount'
	];

	public function import()
	{
		return $this->belongsTo(Import::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
