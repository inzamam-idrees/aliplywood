<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'date',
        'purchase_no',
        'status',
        'total_products',
        'total',
    ];

    protected $casts = [
        'date'       => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // public function purchaseDetails()
    // {
    //     return $this->hasMany(PurchaseDetail::class);
    // }

    public function details()
    {
        return $this->hasMany(PurchaseDetails::class);
    }
}

