<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Import
 * 
 * @property int $id
 * @property int $suppiler_id
 * @property int|null $employee_id
 * @property string|null $status
 * @property Carbon|null $created_at
 * 
 * @property Supplier $supplier
 * @property Collection|ImportDetail[] $import_details
 *
 * @package App\Models
 */
class Import extends Model
{
	protected $table = 'import';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'suppiler_id' => 'int',
		'employee_id' => 'int'
	];

	protected $fillable = [
		'suppiler_id',
		'employee_id',
		'status'
	];

	public function supplier()
	{
		return $this->belongsTo(Supplier::class, 'suppiler_id');
	}

	public function import_details()
	{
		return $this->hasMany(ImportDetail::class);
	}
}
