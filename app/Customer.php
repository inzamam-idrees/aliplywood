<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'address',
        'photo',
        'details',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function invoice(){
        return $this->hasMany('App\Invoice');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
