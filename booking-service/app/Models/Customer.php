<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public $timestamps = true;

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($customer) {
            $customer->bookings()->delete();
        });
    }
}
