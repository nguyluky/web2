<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Warranty
 * 
 * @property int $id
 * @property int $product_id
 * @property string|null $issue_date
 * @property string|null $expiration_date
 * @property string|null $status
 * @property string|null $note
 * 
 * @property OrderDetail $order_detail
 * @property Supplier $supplier
 *
 * @package App\Models
 */
class Warranty extends Model
{
	protected $table = 'warranty';
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
	];

	protected $fillable = [
		'product_id',
		'issue_date',
		'expiration_date',
		'status',
		'note'
	];

	public function order_detail()
	{
		return $this->belongsTo(OrderDetail::class, 'product_id');
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}
}
