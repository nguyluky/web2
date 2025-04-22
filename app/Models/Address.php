<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * 
 * @property int $id
 * @property int $profile_id
 * @property string $phone_number
 * @property string|null $street
 * @property string|null $ward
 * @property string|null $district
 * @property string|null $city
 * 
 * @property Profile $profile
 *
 * @package App\Models
 */
class Address extends Model
{
	protected $table = 'address';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'profile_id' => 'int'
	];

	protected $fillable = [
		'profile_id',
		'phone_number',
		'street',
		'ward',
		'district',
		'city'
	];

	public function profile()
	{
		return $this->belongsTo(Profile::class);
	}
}
