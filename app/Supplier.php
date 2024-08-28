<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
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
        'updated_at' => 'datetime'
    ];

    public function product(){
        return $this->hasMany('App\Product');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
