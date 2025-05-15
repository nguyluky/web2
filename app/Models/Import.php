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
 * @property int $supplier_id
 * @property int|null $employee_id
 * @property string|null $status
 * @property Carbon|null $created_at
 * 
 * @property Account|null $account
 * @property Supplier $supplier
 * @property Collection|ImportDetail[] $import_details
 *
 * @package App\Models
 */
class Import extends Model
{
	protected $table = 'import';
	public $timestamps = false;

	protected $casts = [
		'supplier_id' => 'int',
		'employee_id' => 'int'
	];

protected $fillable = ['supplier_id', 'employee_id', 'status', 'created_at'];

	public function account()
	{
		return $this->belongsTo(Account::class, 'employee_id');
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class, 'supplier_id');
	}

	public function import_details()
	{
		return $this->hasMany(ImportDetail::class);
	}

	public function importDetails()
    {
        return $this->hasMany(ImportDetail::class, 'import_id');
    }
}
