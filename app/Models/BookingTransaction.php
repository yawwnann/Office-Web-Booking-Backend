<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone_number',
        'booking_trx_id',
        'is_paid',
        'started_at',
        'total_amount',
        'duration',
        'ended_at',
        'office_space_id',
    ];

    /**
     * Generate Unique Booking Transaction ID
     *
     * @return string
     */
    public static function generateUniqueTrxId()
    {
        $prefix = 'FO';
        do {
            $randomString = $prefix . mt_rand(100000, 999999);
        } while (self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

    /**
     * Relasi ke OfficeSpace
     *
     * @return BelongsTo
     */
    public function officeSpace(): BelongsTo
    {
        return $this->belongsTo(OfficeSpace::class, 'office_space_id');
    }
}
