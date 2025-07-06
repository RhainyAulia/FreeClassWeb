<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanTable extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->string('kode_peminjaman')->unique();
            $table->string('nama_peminjam');
            $table->string('jabatan');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedBigInteger('id_ruangan');
            $table->unsignedBigInteger('id_slot');
            $table->string('tujuan');
            $table->integer('jumlah_orang');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->timestamps();

            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_slot')->references('id_slot')->on('slot_waktu')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
}