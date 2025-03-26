<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeSpacePhoto extends Model
{
    // Menambahkan kolom 'photo' ke dalam fillable
    protected $fillable = [
        'photo',  // Melakukan asign masal untuk bagian foto

    ];

    // Jika Anda menggunakan relasi, pastikan mendefinisikannya
    public function officeSpace()
    {
        return $this->belongsTo(OfficeSpace::class);
    }
}
