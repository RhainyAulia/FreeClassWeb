<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required|string',
            'jabatan' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'id_ruangan' => 'required|integer',
            'id_slot' => 'required|integer',
            'tujuan' => 'required|string',
            'jumlah_orang' => 'required|integer',
        ]);

        // Buat kode acak
        $kode = 'PIN-' . strtoupper(substr(md5(uniqid()), 0, 6));
        $validated['kode_peminjaman'] = $kode;

        // Simpan ke DB
        $peminjaman = Peminjaman::create($validated);

        return response()->json([
            'message' => 'Peminjaman berhasil',
            'data' => $peminjaman,
        ], 201);
    }

    public function showByKode($kode)
    {
        $data = Peminjaman::where('kode_peminjaman', $kode)->first();

        if (!$data) {
            return response()->json([
                'message' => 'Kode peminjaman tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Data ditemukan',
            'data' => $data
        ]);
    }

    public function batalkan($kode)
    {
        $peminjaman = Peminjaman::where('kode_peminjaman', $kode)->first();

        if (!$peminjaman) {
            return response()->json(['message' => 'Peminjaman tidak ditemukan'], 404);
        }

        if ($peminjaman->status != 'Menunggu') {
            return response()->json(['message' => 'Peminjaman tidak bisa dibatalkan'], 400);
        }

        $peminjaman->status = 'Ditolak';
        $peminjaman->save();

        return response()->json(['message' => 'Peminjaman berhasil dibatalkan']);
    }
    
}
