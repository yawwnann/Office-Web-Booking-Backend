<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class City extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'photo',
        'slug',
    ];

    // Menambahkan event untuk mengisi slug secara otomatis
    protected static function booted()
    {
        static::creating(function ($city) {
            $city->slug = Str::slug($city->name);
        });
    }
}
