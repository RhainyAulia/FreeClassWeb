<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';

    protected $fillable = [
        'nama', 'lokasi', 'kapasitas'
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_ruangan');
    }

    public function jadwalRutin()
    {
        return $this->hasMany(JadwalRutin::class, 'id_ruangan');
    }
}
