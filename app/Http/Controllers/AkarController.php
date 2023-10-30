<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AkarBilangan; // Import model

class AkarController extends Controller
{
    public function hitungAkar(Request $request)
    {
        // Validasi input dari pengguna
        $validator = Validator::make($request->all(), [
            'bilangan' => 'required|numeric|min:0',
        ]);
        // $id = auth()->user()->id;
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $bilangan = $request->input('bilangan');
        $user_id = $request->input('user_id');

        $waktuPemrosesanAwal = microtime(true);

        // Inisialisasi $kuadrat_manual
        $hasil_kuadrat = 0;

        if ($bilangan > 0) {
            // Perhitungan manual akar kuadrat
            $estimasi = $bilangan / 2;
            for ($i = 0; $i < 1000; $i++) {
                $estimasi_baru = 0.5 * ($estimasi + $bilangan / $estimasi);
                if (abs($estimasi_baru - $estimasi) < 1e-6) {
                    $hasil_kuadrat = $estimasi_baru;
                    break;
                }
                $estimasi = $estimasi_baru;
            }
        }

        $waktuPemrosesanAkhir = microtime(true);
        $waktuPemrosesan = round(($waktuPemrosesanAkhir - $waktuPemrosesanAwal) * 1000, 6); // Waktu dalam milidetik dengan 6 desimal
        $waktuPemrosesan = number_format($waktuPemrosesan, 6, '.', ''); // Format bilangan desimal

        // Simpan ke database
        $akarBilangan = new AkarBilangan();
        $akarBilangan->bilangan = $bilangan;
        $akarBilangan->akar =  $hasil_kuadrat;
        $akarBilangan->waktu_pemrosesan = $waktuPemrosesan;
        $akarBilangan->user_id = $user_id;
        $akarBilangan->save();

        return response()->json([
            'bilangan' => $bilangan,
            'akar' => $hasil_kuadrat,
            'waktu_pemrosesan' => $waktuPemrosesan,
            'user_id' => $user_id
        ]);

    }

    public function getAllData()
    {
        $akarBilangan = AkarBilangan::all();

        return response()->json($akarBilangan);
    }

    public function getLowestProcessingTime()
    {
        // Menggunakan Eloquent untuk mengambil entri dengan waktu pemrosesan terkecil (ASC)
        $lowestProcessingTimeEntry = AkarBilangan::orderBy('waktu_pemrosesan', 'asc')->first();

        if ($lowestProcessingTimeEntry) {
            return response()->json($lowestProcessingTimeEntry);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }
    }

    public function getHighestProcessingTime()
    {
        // Menggunakan Eloquent untuk mengambil entri dengan waktu pemrosesan terbesar (DESC)
        $highestProcessingTimeEntry = AkarBilangan::orderBy('waktu_pemrosesan', 'desc')->first();

        if ($highestProcessingTimeEntry) {
            return response()->json($highestProcessingTimeEntry);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }
    }

    public function getDataByUserId(Request $request)
    {
        // Validasi input dari pengguna
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user_id = $request->input('user_id');

        // Menggunakan Eloquent untuk mengambil data akar bilangan berdasarkan user_id
        $akarBilanganByUser = AkarBilangan::where('user_id', $user_id)->get();

        if ($akarBilanganByUser->count() > 0) {
            return response()->json($akarBilanganByUser);
        } else {
            return response()->json(['message' => 'No data found for the specified user_id'], 404);
        }
    }

    public function getAverageProcessingTime()
    {
        // Jalankan query SQL untuk menghitung rata-rata waktu pemrosesan
        $result = AkarBilangan::selectRaw('AVG(waktu_pemrosesan) AS rata_rata_waktu_pemrosesan')->first();

        if ($result) {
            return response()->json($result);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }
    }   
    
    public function getUserData()
    {
        // Menggunakan Eloquent untuk mengambil data user dan jumlah bilangan yang diinput
        $userData = AkarBilangan::selectRaw('users.nim as nim, COUNT(akar_bilangans.id) as jumlah_bilangan')
            ->join('users', 'akar_bilangans.user_id', '=', 'users.id')
            ->groupBy('users.nim')
            ->get();
    
        if ($userData->count() > 0) {
            return response()->json($userData);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }
    }
    

}
