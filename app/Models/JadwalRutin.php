<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRutin extends Model
{
    use HasFactory;

    protected $table = 'jadwal_rutin';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_ruangan', 'hari', 'jam_mulai', 'jam_selesai', 'mata_kuliah', 'dosen'
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
}
