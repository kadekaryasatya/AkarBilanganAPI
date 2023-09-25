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
        $akar = sqrt($bilangan);
        $waktuPemrosesanAkhir = microtime(true);
        $waktuPemrosesan = round(($waktuPemrosesanAkhir - $waktuPemrosesanAwal) * 1000, 6); // Waktu dalam milidetik dengan 6 desimal
        $waktuPemrosesan = number_format($waktuPemrosesan, 6, '.', ''); // Format bilangan desimal

        // Simpan ke database
        $akarBilangan = new AkarBilangan();
        $akarBilangan->bilangan = $bilangan;
        $akarBilangan->akar = $akar;
        $akarBilangan->waktu_pemrosesan = $waktuPemrosesan;
        $akarBilangan->save();

        return response()->json([   
            'bilangan' => $bilangan,
            'akar' => $akar,
            'waktu_pemrosesan' => $waktuPemrosesan,
        ]);
    }

    public function getAllData()
    {
        $akarBilangan = AkarBilangan::all();

        return response()->json($akarBilangan);
    }

}
