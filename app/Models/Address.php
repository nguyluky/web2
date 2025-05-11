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
 * @property string $email
 * @property string $name
 * @property string|null $street
 * @property string|null $ward
 * @property string|null $district
 * @property string|null $cit
 * 
 * @property Profile $profile
 *
 * @package App\Models
 */
class Address extends Model
{
	protected $table = 'address';
	public $timestamps = false;

	protected $casts = [
		'profile_id' => 'int'
	];

	protected $fillable = [
		'profile_id',
		'phone_number',
        'email',
        'name',
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
