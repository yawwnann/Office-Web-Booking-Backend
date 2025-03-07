<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OfficeSpace extends Model
{
    use SoftDeletes;

    // Kolom yang dapat di-assign massal
    protected $fillable = [
        'name',
        'thumbnail',
        'is_open',
        'is_full_booked',
        'price',
        'duration',
        'address',
        'about',
        'slug',
        'city_id',
    ];

    // Menambahkan setter untuk name yang otomatis mengubah slug
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;

        // Membuat slug berdasarkan nama
        $slug = Str::slug($value);

        // Menambahkan pengecekan untuk duplikasi slug
        $existingSlugCount = OfficeSpace::where('slug', $slug)->count();
        if ($existingSlugCount > 0) {
            // Jika slug sudah ada, tambahkan angka untuk membuatnya unik
            $slug = $slug . '-' . ($existingSlugCount + 1);
        }

        // Menetapkan slug ke atribut
        $this->attributes['slug'] = $slug;
    }
    // Relasi HasMany untuk photos
    public function photos(): HasMany
    {
        return $this->hasMany(OfficeSpacePhoto::class);
    }

    // Relasi HasMany untuk benefits
    public function benefits(): HasMany
    {
        return $this->hasMany(OfficeSpaceBenefit::class);
    }

    // Relasi BelongsTo untuk city
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
