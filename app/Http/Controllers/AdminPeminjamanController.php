<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPeminjamanController extends Controller
{
    public function dashboard()
    {
        try {
            // Waktu sekarang
            $now = Carbon::now();
            $today = now()->toDateString(); // 2025-07-06
            $timeNow = now()->format('H:i:s'); // 10:15:00 (pastikan tanpa microsecond!)
            $hari = now()->locale('id')->dayName; // Senin, dst

            // Total kelas aktif
            $totalKelas = DB::table('ruangan')
                ->where('jenis_ruangan', 'Kelas')
                ->where('status_aktif', 1)
                ->count();

            // Kelas dipakai (jadwal rutin + peminjaman disetujui)
            $jadwalRutin = DB::table('jadwal_rutin as jr')
                ->join('slot_waktu as sw', 'jr.id_slot', '=', 'sw.id_slot')
                ->join('ruangan as r', 'jr.id_ruangan', '=', 'r.id_ruangan')
                ->where('r.jenis_ruangan', 'Kelas')
                ->where('r.status_aktif', 1)
                ->where('jr.hari', $hari)
                ->whereDate('jr.tanggal_mulai_efektif', '<=', $today)
                ->whereDate('jr.tanggal_selesai_efektif', '>=', $today)
                ->whereRaw('? BETWEEN sw.jam_mulai AND sw.jam_selesai', [$timeNow])
                ->select('r.id_ruangan');


            $peminjaman = DB::table('peminjaman as p')
                ->join('ruangan as r', 'p.id_ruangan', '=', 'r.id_ruangan')
                ->where('r.jenis_ruangan', 'Kelas')
                ->where('r.status_aktif', 1)
                ->where('p.status', 'Disetujui')
                ->whereDate('p.tanggal', $today)
                ->whereRaw('? BETWEEN p.jam_mulai AND p.jam_selesai', [$timeNow])
                ->select('r.id_ruangan');

            $kelasDipakai = DB::table(DB::raw('
                (
                    SELECT jr.id_ruangan
                    FROM jadwal_rutin jr
                    JOIN slot_waktu sw ON jr.id_slot = sw.id_slot
                    JOIN ruangan r ON jr.id_ruangan = r.id_ruangan
                    WHERE r.jenis_ruangan = "Kelas"
                    AND r.status_aktif = 1
                    AND jr.hari = "' . $hari . '"
                    AND "' . $today . '" BETWEEN jr.tanggal_mulai_efektif AND jr.tanggal_selesai_efektif
                    AND TIME("' . $timeNow . '") >= sw.jam_mulai
                    AND TIME("' . $timeNow . '") < sw.jam_selesai

                    UNION

                    SELECT p.id_ruangan
                    FROM peminjaman p
                    JOIN ruangan r ON p.id_ruangan = r.id_ruangan
                    WHERE r.jenis_ruangan = "Kelas"
                    AND r.status_aktif = 1
                    AND p.status = "Disetujui"
                    AND p.tanggal = "' . $today . '"
                    AND TIME("' . $timeNow . '") >= p.jam_mulai
                    AND TIME("' . $timeNow . '") < p.jam_selesai
                ) AS ruangan_dipakai
            '))
            ->distinct()
            ->count('id_ruangan');

            // Pending request
            $pendingRequest = DB::table('peminjaman')
                ->where('status', 'Menunggu')
                ->count();

            // Data peminjaman untuk dashboard
            $data = DB::table('peminjaman')
                ->join('ruangan', 'peminjaman.id_ruangan', '=', 'ruangan.id_ruangan')
                ->where('peminjaman.status', 'Menunggu')
                ->select(
                    'peminjaman.id_peminjaman',
                    'peminjaman.kode_peminjaman',
                    'peminjaman.nama_peminjam',
                    'peminjaman.jabatan',
                    'peminjaman.tanggal',
                    'peminjaman.jam_mulai',
                    'peminjaman.jam_selesai',
                    'peminjaman.id_slot',
                    'ruangan.nama_ruangan',
                    'peminjaman.tujuan',
                    'peminjaman.jumlah_orang'
                )
                ->get();

            return view('dashboard', compact('totalKelas', 'kelasDipakai', 'pendingRequest', 'data'));

        } catch (\Exception $e) {
            return view('dashboard', [
                'totalKelas' => 0,
                'kelasDipakai' => 0,
                'pendingRequest' => 0,
                'data' => [],
                'error' => 'Gagal mengambil data: ' . $e->getMessage()
            ]);
        }
    }

    public function setujui($id, Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('peminjaman')->where('id_peminjaman', $id)->update([
                'status' => 'Disetujui',
                'id_ruangan' => $request->id_ruangan,
            ]);

            DB::table('log_peminjaman_status')->insert([
                'id_peminjaman' => $id,
                'status' => 'Disetujui',
                'catatan' => $request->catatan ?? null,
                'created_at' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function tolak($id, Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('peminjaman')->where('id_peminjaman', $id)->update([
                'status' => 'Ditolak'
            ]);

            DB::table('log_peminjaman_status')->insert([
                'id_peminjaman' => $id,
                'status' => 'Ditolak',
                'catatan' => $request->catatan ?? null,
                'created_at' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function filter(Request $request)
    {
        $query = DB::table('peminjaman')
            ->join('ruangan', 'peminjaman.id_ruangan', '=', 'ruangan.id_ruangan')
            ->where('peminjaman.status', 'Menunggu');

        if ($request->has('hari_ini') && $request->hari_ini === '1') {
            $query->whereDate('peminjaman.tanggal', Carbon::today());
        }

        if ($request->has('jabatan') && in_array(strtolower($request->jabatan), ['mahasiswa', 'dosen'])) {
            $query->where('peminjaman.jabatan', ucfirst(strtolower($request->jabatan)));
        }

        $data = $query->select(
                'peminjaman.id_peminjaman',
                'peminjaman.kode_peminjaman',
                'peminjaman.nama_peminjam',
                'peminjaman.jabatan',
                'peminjaman.tanggal',
                'peminjaman.jam_mulai',
                'peminjaman.jam_selesai',
                'peminjaman.id_slot',
                'ruangan.nama_ruangan',
                'peminjaman.tujuan',
                'peminjaman.jumlah_orang'
            )
            ->orderBy('peminjaman.tanggal')
            ->orderBy('peminjaman.jam_mulai')
            ->get();

        return response()->json($data);
    }
}
