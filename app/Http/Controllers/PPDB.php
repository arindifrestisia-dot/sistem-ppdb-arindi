<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PPDB extends Controller
{
    // Halaman info PPDB
    public function info()
    {
        return view('ppdb.info');
    }

    // Halaman formulir pendaftaran
    public function formulir()
    {
        return view('ppdb.formulir');
    }

    // Halaman pembayaran
    public function pembayaran(Request $request)
    {
        // Contoh ambil data
        $paket = $request->paket;
        $harga = $request->harga;
        $nama  = $request->nama;

        return view('ppdb.pembayaran', compact('paket', 'harga', 'nama'));
    }

    // Konfirmasi pembayaran
    public function konfirmasi(Request $request)
    {
        $request->validate([
            'bukti' => 'required|image|mimes:jpg,png|max:2048',
            'nama_pengirim' => 'required',
            'tanggal_transfer' => 'required|date'
        ]);
        
        // Simpan bukti (opsional)
        $path = $request->file('bukti')->store('bukti-pembayaran', 'public');
        
        // Simpan status sukses ke session
        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil, transaksi selesai',
            'redirect' => route('ppdb.formulir_form_pendaftaran')
        ]);
    }

    public function formulir_PPDB()
    {
        return view('ppdb.formulir-ppdb');
    }
    

}