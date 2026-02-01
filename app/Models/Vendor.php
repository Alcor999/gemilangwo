<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'vendor_category_id',
        'name',
        'description',
        'price',
        'image',
        'contact_phone',
        'contact_email',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategory::class);
    }
}
