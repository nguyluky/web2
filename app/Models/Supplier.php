<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Supplier
 * 
 * @property int $id
 * @property string $name
 * @property string|null $tax
 * @property string|null $contact_name
 * @property string $phone_number
 * @property string|null $email
 * @property string $status
 * @property Carbon $created_at
 * 
 * @property Collection|Import[] $imports
 * @property Collection|Warranty[] $warranties
 *
 * @package App\Models
 */
class Supplier extends Model
{
	protected $table = 'supplier';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'name',
		'tax',
		'contact_name',
		'phone_number',
		'email',
		'status'
	];

	public function imports()
	{
		return $this->hasMany(Import::class, 'suppiler_id');
	}

	public function warranties()
	{
		return $this->hasMany(Warranty::class);
	}
}
