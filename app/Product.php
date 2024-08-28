<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $guarded = ['id'];

    public $fillable = [
        'name',
        'slug',
        'serial_number',
        'quantity',
        'quantity_alert',
        'buying_price',
        'selling_price',
        'tax_id',
        'notes',
        'image',
        'category_id',
        'unit_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(){
       return $this->belongsTo('App\Category');
    }
    public function unit(){
        return $this->belongsTo('App\Unit');
    }
    public function tax(){
        return $this->belongsTo('App\Tax');
    }

    public function additionalProduct(){
        return $this->hasMany('App\ProductSupplier');
    }

    public function sale(){
        return $this->hasMany('App\Sale');
    }

    public function invoice(){
        return $this->belongsToMany('App\Invoice');
    }

    // protected function buyingPrice(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value / 100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }

    // protected function sellingPrice(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value / 100,
    //         set: fn ($value) => $value * 100,
    //     );
    // }

    public function getBuyingPriceAttribute($value)
    {
        return $value / 100;
    }

    // Mutator for buying_price
    public function setBuyingPriceAttribute($value)
    {
        $this->attributes['buying_price'] = $value * 100;
    }

    // Accessor for selling_price
    public function getSellingPriceAttribute($value)
    {
        return $value / 100;
    }

    // Mutator for selling_price
    public function setSellingPriceAttribute($value)
    {
        $this->attributes['selling_price'] = $value * 100;
    }
}
