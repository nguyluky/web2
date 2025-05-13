<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $casts = [
        'account_id' => 'int', // Sửa profile_id thành account_id
        'payment_method' => 'string'
    ];

    protected $fillable = [
        'account_id', // Sửa profile_id thành account_id
        'status',
        'payment_method',
        'employee_id'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_method');
    }

    public function profile()
    {
        // Liên kết qua account
        return $this->hasOneThrough(Profile::class, Account::class, 'id', 'id', 'account_id', 'id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
}