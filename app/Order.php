<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'customer_id',
        'order_date',
        'order_status',
        'total_products',
        'sub_total',
        'discount',
        'total',
        'invoice_no',
        'payment_type',
        'pay',
        'due',
    ];

    protected $casts = [
        'order_date'    => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];

    // public function invoice()
    // {
    //     return $this->belongsTo('App\Invoice');
    // }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function details()
    {
        return $this->hasMany(OrderDetails::class);
    }

    // public function product()
    // {
    //     return $this->belongsTo('App\Product');
    // }


}
