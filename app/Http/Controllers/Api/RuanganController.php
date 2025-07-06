<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RuanganController extends Controller
{
    public function ruanganTerpakai(Request $request)
    {
        try {
            $hari = match (Carbon::now()->dayOfWeekIso) {
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu',
            };

            $nowDate = Carbon::now()->toDateString();
            $nowTime = Carbon::now()->format('H:i:s');

            // Query jadwal_rutin
            $jadwalRutin = DB::table('jadwal_rutin as jr')
                ->join('slot_waktu as sw', 'jr.id_slot', '=', 'sw.id_slot')
                ->join('ruangan as r', 'jr.id_ruangan', '=', 'r.id_ruangan')
                ->where('r.jenis_ruangan', 'Kelas')
                ->where('r.status_aktif', 1)
                ->where('jr.hari', $hari)
                ->whereDate('jr.tanggal_mulai_efektif', '<=', $nowDate)
                ->whereDate('jr.tanggal_selesai_efektif', '>=', $nowDate)
                ->whereRaw('? BETWEEN sw.jam_mulai AND sw.jam_selesai', [$nowTime])
                ->select(
                    'r.id_ruangan',
                    DB::raw("CONCAT('Ruang ', r.nama_ruangan) AS nama_ruangan"),
                    DB::raw("CONCAT(sw.jam_mulai, ' s/d ', sw.jam_selesai) AS waktu")
                );

            // Query peminjaman
            $peminjaman = DB::table('peminjaman as p')
                ->join('ruangan as r', 'p.id_ruangan', '=', 'r.id_ruangan')
                ->where('r.jenis_ruangan', 'Kelas')
                ->where('r.status_aktif', 1)
                ->where('p.status', 'Disetujui')
                ->whereDate('p.tanggal', $nowDate)
                ->whereRaw('? BETWEEN p.jam_mulai AND p.jam_selesai', [$nowTime])
                ->select(
                    'r.id_ruangan',
                    DB::raw("CONCAT('Ruang ', r.nama_ruangan) AS nama_ruangan"),
                    DB::raw("CONCAT(p.jam_mulai, ' s/d ', p.jam_selesai) AS waktu")
                );

            $ruanganTerpakai = $jadwalRutin->union($peminjaman)->get();

            return response()->json($ruanganTerpakai);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
}

}
