<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeSpacePhoto extends Model
{
    // Menambahkan kolom 'photo' ke dalam fillable
    protected $fillable = [
        'photo',  // Menambahkan kolom photo agar dapat di-assign massal
        // Kolom lainnya jika ada, misalnya:
        // 'office_space_id',
    ];

    // Jika Anda menggunakan relasi, pastikan mendefinisikannya
    public function officeSpace()
    {
        return $this->belongsTo(OfficeSpace::class);
    }
}
