<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_type_id',
        'name',
        'slug',
        'description',
        'price',
        'engine',
        'power',
        'color',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    public function type()
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    public function scopeActive(Builder $query)
{
    return $query->where('is_active', true);
}
}
