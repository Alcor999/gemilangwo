<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function vendors()
    {
        return $this->hasMany(Vendor::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function allVendors()
    {
        return $this->hasMany(Vendor::class)->orderBy('sort_order');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_vendor_category');
    }

    public static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Str::slug($model->name);
            }
        });
    }
}
