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

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $bilangan = $request->input('bilangan');
        $waktuPemrosesanAwal = microtime(true);
        
        // Inisialisasi $kuadrat_manual
        $kuadrat_manual = 0;

        // Perhitungan manual akar kuadrat
        $x = $bilangan / 2;
        for ($i = 0; $i < 1000; $i++) { // Batasi iterasi ke 1000 untuk menghindari perulangan tak terbatas
            $estimate = 0.5 * ($x + $bilangan / $x);
            if (abs($estimate - $x) < 1e-6) {
                $kuadrat_manual = $estimate;
                break;
            }
            $x = $estimate;
        }

        $waktuPemrosesanAkhir = microtime(true);
        $waktuPemrosesan = round(($waktuPemrosesanAkhir - $waktuPemrosesanAwal) * 1000, 6); // Waktu dalam milidetik dengan 6 desimal
        $waktuPemrosesan = number_format($waktuPemrosesan, 6, '.', ''); // Format bilangan desimal

        // Simpan ke database
        $akarBilangan = new AkarBilangan();
        $akarBilangan->bilangan = $bilangan;
        $akarBilangan->akar =  $kuadrat_manual;
        $akarBilangan->waktu_pemrosesan = $waktuPemrosesan;
        $akarBilangan->save();

        return response()->json([   
            'bilangan' => $bilangan,
            'akar' => $kuadrat_manual,
            'waktu_pemrosesan' => $waktuPemrosesan,
        ]);
    }

    public function getAllData()
    {
        $akarBilangan = AkarBilangan::all();

        return response()->json($akarBilangan);
    }

}
