<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderVendor extends Model
{
    protected $fillable = [
        'order_id',
        'vendor_id',
        'vendor_category_id',
        'vendor_name',
        'vendor_category_name',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategory::class);
    }
}
