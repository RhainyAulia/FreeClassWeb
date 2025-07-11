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
        'kode_peminjaman' => 'nullable|string|unique:peminjaman,kode_peminjaman',
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

    // 🔑 Gunakan kode dari frontend jika ada, kalau tidak buat sendiri
    $validated['kode_peminjaman'] = $validated['kode_peminjaman'] ?? 'FC' . now()->format('YmdHis');

    $peminjaman = Peminjaman::create($validated);

    return response()->json([
        'message' => 'Peminjaman berhasil',
        'data' => $peminjaman,
    ], 201);
}


public function showByKode($kode)
{
    $data = DB::table('peminjaman')
        ->where('kode_peminjaman', $kode)
        ->first();

    if (!$data) {
        return response()->json([
            'message' => 'Kode peminjaman tidak ditemukan'
        ], 404);
    }

    // Ambil ruangan dari tabel ruangan (berdasarkan id_ruangan dari tabel peminjaman)
    $ruangan = null;
    if ($data->id_ruangan) {
        $ruangan = DB::table('ruangan')
            ->where('id_ruangan', $data->id_ruangan)
            ->first();
    }

    // Ambil catatan terbaru dari log (jika ada)
    $log = DB::table('log_peminjaman_status')
        ->where('id_peminjaman', $data->id_peminjaman)
        ->orderByDesc('created_at')
        ->first();

    return response()->json([
        'message' => 'Data ditemukan',
        'data' => [
            'kode_peminjaman' => $data->kode_peminjaman,
            'status' => $data->status,
            'nama_peminjam' => $data->nama_peminjam,
            'tanggal' => $data->tanggal,
            'jam_mulai' => $data->jam_mulai,
            'jam_selesai' => $data->jam_selesai,
            'tujuan' => $data->tujuan,
            'kode_ruangan' => $ruangan->kode_ruangan ?? '-',
            'nama_ruangan' => $ruangan->nama_ruangan ?? '-',
            'catatan' => $log->catatan ?? '-',
        ]
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