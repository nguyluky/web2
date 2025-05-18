<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable; // Import Authenticatable
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Account
 * 
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int|null $rule
 * @property string $status
 * @property Carbon $created
 * @property Carbon $updated
 * @property string|null $deleted_at
 * 
 * @property Collection|Import[] $imports
 * @property Collection|ProductReview[] $product_reviews
 * @property Profile|null $profile
 *
 * @package App\Models
 */
class Account extends Authenticatable implements JWTSubject
{
	use SoftDeletes;
	
	protected $table = 'account';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $casts = [
		'rule' => 'int',
		'created' => 'datetime',
		'updated' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'password',
		'rule',
		'status',
		'created',
		'updated'
	];

	public function rule()
	{
		return $this->belongsTo(Rule::class, 'rule');
	}

	public function imports()
	{
		return $this->hasMany(Import::class, 'employee_id');
	}

	public function product_reviews()
	{
		return $this->hasMany(ProductReview::class, 'user_id');
	}

	public function profile()
	{
		return $this->hasOne(Profile::class, 'id');
	}

	// Add the required methods for JWTSubject
	public function getJWTIdentifier()
	{
		return $this->getKey(); // Return the primary key of the user
	}

	public function getJWTCustomClaims()
	{
		return []; // Add any custom claims if needed
	}

	public function orders()
    {
        return $this->hasMany(Order::class, 'account_id', 'id');
    }

    public function isAdmin()
    {
        return $this->rule === 1; // Assuming rule 1 is for admin
    }
}
