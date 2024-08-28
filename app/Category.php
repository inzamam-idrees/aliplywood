<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(){
        return $this->hasMany('App\Product');
    }

    public function products(){
        return $this->hasMany('App\Product');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
