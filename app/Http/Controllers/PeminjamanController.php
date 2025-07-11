<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Buat kode
        $validated['kode_peminjaman'] = 'FC' . now()->format('YmdHis');

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

    public function index()
    {
        $peminjaman = Peminjaman::all();
        return response()->json($peminjaman);
    }

    public function ruanganTerpakai()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->format('H:i:s');
        $hari = $now->locale('id')->dayName;
        $timeNow = $now->format('H:i:s');
        $hari = $now->locale('id')->dayName;

        // Dari jadwal rutin
        $jadwalRutin = DB::table('jadwal_rutin as jr')
            ->join('slot_waktu as sw', 'jr.id_slot', '=', 'sw.id_slot')
            ->join('ruangan as r', 'jr.id_ruangan', '=', 'r.id_ruangan')
            ->where('r.status_aktif', 1)
            ->where('jr.hari', $hari)
            ->whereDate('jr.tanggal_mulai_efektif', '<=', $today)
            ->whereDate('jr.tanggal_selesai_efektif', '>=', $today)
            ->whereRaw('? BETWEEN sw.jam_mulai AND sw.jam_selesai', [$timeNow])
            ->select('r.id_ruangan', 'r.nama_ruangan as nama', 'r.lokasi', 'r.kapasitas');

        // Dari peminjaman yang disetujui
        $peminjaman = DB::table('peminjaman as p')
            ->join('ruangan as r', 'p.id_ruangan', '=', 'r.id_ruangan')
            ->where('r.status_aktif', 1)
            ->where('p.status', 'Disetujui')
            ->whereDate('p.tanggal', $today)
            ->whereRaw('? BETWEEN p.jam_mulai AND p.jam_selesai', [$timeNow])
            ->select('r.id_ruangan', 'r.nama_ruangan as nama', 'r.lokasi', 'r.kapasitas');

        $result = $jadwalRutin->union($peminjaman)->get();

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);

    }
}