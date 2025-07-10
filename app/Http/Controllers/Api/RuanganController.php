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
            $tanggal = $request->query('tanggal', Carbon::now()->toDateString());
            $idSlot = $request->query('id_slot');

            if (!$idSlot) {
                return response()->json(['error' => 'Parameter id_slot diperlukan'], 400);
            }

            $hari = Carbon::parse($tanggal)->locale('id')->dayName;

            // Ambil slot waktu
            $slot = DB::table('slot_waktu')->where('id_slot', $idSlot)->first();
            if (!$slot) {
                return response()->json(['error' => 'Slot tidak ditemukan'], 404);
            }

            // Jadwal Rutin
            $jadwalRutin = DB::table('jadwal_rutin as jr')
                ->join('slot_waktu as sw', 'jr.id_slot', '=', 'sw.id_slot')
                ->join('ruangan as r', 'jr.id_ruangan', '=', 'r.id_ruangan')
                ->where('r.jenis_ruangan', 'Kelas')
                ->where('r.status_aktif', 1)
                ->where('jr.hari', $hari)
                ->whereDate('jr.tanggal_mulai_efektif', '<=', $tanggal)
                ->whereDate('jr.tanggal_selesai_efektif', '>=', $tanggal)
                ->where('jr.id_slot', $idSlot)
                ->select(
                    'r.id_ruangan',
                    DB::raw("CONCAT('Ruang ', r.nama_ruangan) AS nama_ruangan"),
                    DB::raw("CONCAT(sw.jam_mulai, ' s/d ', sw.jam_selesai) AS waktu")
                );

            // Peminjaman
            $peminjaman = DB::table('peminjaman as p')
                ->join('ruangan as r', 'p.id_ruangan', '=', 'r.id_ruangan')
                ->where('r.jenis_ruangan', 'Kelas')
                ->where('r.status_aktif', 1)
                ->where('p.status', 'Disetujui')
                ->whereDate('p.tanggal', $tanggal)
                ->where('p.id_slot', $idSlot)
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
    
    public function ruanganTersedia(Request $request)
    {
        $tanggal = $request->query('tanggal');
        $id_slot = $request->query('id_slot');

        $hari = Carbon::parse($tanggal)->locale('id')->dayName;

        $dipakaiJadwal = DB::table('jadwal_rutin')
            ->where('hari', $hari)
            ->where('id_slot', $id_slot)
            ->pluck('id_ruangan');

        $dipakaiPinjam = DB::table('peminjaman')
            ->where('tanggal', $tanggal)
            ->where('id_slot', $id_slot)
            ->where('status', 'Disetujui')
            ->pluck('id_ruangan');

        $dipakai = $dipakaiJadwal->merge($dipakaiPinjam)->unique();

        $tersedia = DB::table('ruangan')
            ->where('jenis_ruangan', 'Kelas')
            ->where('status_aktif', 1)
            ->whereNotIn('id_ruangan', $dipakai)
            ->select('id_ruangan', 'nama_ruangan')
            ->get();

        return response()->json($tersedia);
    }

}