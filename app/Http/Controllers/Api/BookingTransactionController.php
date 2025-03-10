<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Http\Resources\Api\BookingTransactionResource;
use App\Http\Resources\Api\ViewBookingResource;
use App\Models\BookingTransaction;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingTransactionController extends Controller
{
    public function store(StoreBookingTransactionRequest $request)
    {
        Log::info('Menerima request booking:', $request->all());

        try {
            // Validasi request
            $validatedData = $request->validated();
            Log::info('Data setelah validasi:', $validatedData);

            // Cek apakah office space tersedia
            $officeSpace = OfficeSpace::find($validatedData['office_space_id']);
            if (!$officeSpace) {
                Log::error('Office space not found', ['office_space_id' => $validatedData['office_space_id']]);
                return response()->json(['message' => 'Office space not found'], 404);
            }

            // Pastikan metode generateUniqueTrxId ada sebelum dipanggil
            if (!method_exists(BookingTransaction::class, 'generateUniqueTrxId')) {
                Log::error('Method generateUniqueTrxId tidak ditemukan di BookingTransaction');
                return response()->json(['message' => 'Internal Server Error'], 500);
            }

            // Menyiapkan data booking
            $validatedData['is_paid'] = false;
            $validatedData['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();
            $validatedData['duration'] = $officeSpace->duration;
            $validatedData['ended_at'] = (new \DateTime($validatedData['started_at']))
                ->modify("+{$officeSpace->duration} days")
                ->format('Y-m-d');

            // Simpan transaksi booking
            $bookingTransaction = BookingTransaction::create($validatedData);
            Log::info('Booking berhasil dibuat:', $bookingTransaction->toArray());

            // Mengembalikan response sukses
            $bookingTransaction->load('officeSpace');
            return new BookingTransactionResource($bookingTransaction);

        } catch (Exception $e) {
            Log::error('Terjadi kesalahan saat menyimpan transaksi:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function booking_details(Request $request)
    {
        Log::info('Menerima request pengecekan booking:', $request->all());

        try {
            // Validasi request
            $request->validate([
                'booking_trx_id' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
            ]);

            // Mencari booking berdasarkan trx_id dan phone_number
            $booking = BookingTransaction::where('booking_trx_id', $request->booking_trx_id)
                ->where('phone_number', $request->phone_number)
                ->with(['officeSpace', 'officeSpace.city'])
                ->first();

            if (!$booking) {
                Log::warning('Booking tidak ditemukan', [
                    'booking_trx_id' => $request->booking_trx_id,
                    'phone_number' => $request->phone_number
                ]);
                return response()->json(['message' => 'Booking not found'], 404);
            }

            return new ViewBookingResource($booking);

        } catch (Exception $e) {
            Log::error('Terjadi kesalahan saat mengecek booking:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
