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
 * @property ProductVariant $product
 * 
 *
 * @package App\Models
 */
class ImportDetail extends Model
{
	protected $table = 'import_detail';
	public $timestamps = false;

	protected $casts = [
		'import_id' => 'int',
		'product_variant_id' => 'int',
		'import_price' => 'int',
		'amount' => 'int'
	];

	protected $fillable = [
		'import_id',
		'product_variant_id',
		'import_price',
		'amount'
	];

	public function import()
	{
		return $this->belongsTo(Import::class);
	}

	public function product()
	{
		return $this->belongsTo(ProductVariant::class);
	}
}
