<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeSpaceBenefit extends Model
{
    // Menambahkan kolom 'name' ke dalam fillable
    protected $fillable = [
        'name',

    ];

    // Jika Anda menggunakan relasi, pastikan mendefinisikannya
    public function officeSpace()
    {
        return $this->belongsTo(OfficeSpace::class);
    }
}
