<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = ['name', 'key']; // Menambahkan key ke fillable

    protected static function booted()
    {
        static::creating(function ($apiKey) {
            $apiKey->key = Str::random(32);  // Menghasilkan string acak untuk 'key'
        });
    }
}
