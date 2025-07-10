<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'nama_peminjam',
        'jabatan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'id_ruangan',
        'id_slot',
        'tujuan',
        'jumlah_orang',
        'status'
    ];
}
