<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalRutinTable extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_rutin', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->string('hari');
            $table->unsignedBigInteger('id_ruangan');
            $table->unsignedBigInteger('id_slot');
            $table->string('mata_kuliah_kegiatan');
            $table->string('semester');
            $table->string('tahun_ajaran');
            $table->date('tanggal_mulai_efektif');
            $table->date('tanggal_selesai_efektif');
            $table->timestamps();

            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_slot')->references('id_slot')->on('slot_waktu')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_rutin');
    }
}
