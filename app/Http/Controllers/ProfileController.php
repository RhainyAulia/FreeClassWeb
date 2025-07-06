<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function dashboard()
    {
        try {
            // Total ruangan kelas aktif
            $totalKelas = DB::table('ruangan')
                ->where('jenis_ruangan', 'Kelas')
                ->where('status_aktif', 1)
                ->count();

            // Kelas dipakai (jadwal rutin + peminjaman)
            $kelasDipakai = DB::table(DB::raw('
                (
                    SELECT jr.id_ruangan
                    FROM jadwal_rutin jr
                    JOIN slot_waktu sw ON jr.id_slot = sw.id_slot
                    JOIN ruangan r ON jr.id_ruangan = r.id_ruangan
                    WHERE r.jenis_ruangan = "Kelas"
                    AND r.status_aktif = 1
                    AND jr.hari = CASE DAYOFWEEK(CURDATE())
                        WHEN 1 THEN "Minggu"
                        WHEN 2 THEN "Senin"
                        WHEN 3 THEN "Selasa"
                        WHEN 4 THEN "Rabu"
                        WHEN 5 THEN "Kamis"
                        WHEN 6 THEN "Jumat"
                        WHEN 7 THEN "Sabtu"
                    END
                    AND CURDATE() BETWEEN jr.tanggal_mulai_efektif AND jr.tanggal_selesai_efektif
                    AND CURTIME() BETWEEN sw.jam_mulai AND sw.jam_selesai

                    UNION

                    SELECT p.id_ruangan
                    FROM peminjaman p
                    JOIN ruangan r ON p.id_ruangan = r.id_ruangan
                    WHERE r.jenis_ruangan = "Kelas"
                    AND r.status_aktif = 1
                    AND p.status = "Disetujui"
                    AND p.tanggal = CURDATE()
                    AND CURTIME() BETWEEN p.jam_mulai AND p.jam_selesai
                ) AS ruangan_dipakai
            '))
            ->distinct()
            ->count('id_ruangan');


            // Pending request
            $pendingRequest = DB::table('peminjaman')
                ->where('status', 'Menunggu')
                ->count();

            return view('dashboard', compact('totalKelas', 'kelasDipakai', 'pendingRequest'));

        } catch (\Exception $e) {
            return view('dashboard', [
                'totalKelas' => 0,
                'kelasDipakai' => 0,
                'pendingRequest' => 0,
                'error' => 'Gagal mengambil data: ' . $e->getMessage()
            ]);
        }
    }
}
