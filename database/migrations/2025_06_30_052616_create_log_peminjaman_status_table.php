<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPeminjamanStatusTable extends Migration
{
    public function up(): void
    {
        Schema::create('log_peminjaman_status', function (Blueprint $table) {
            $table->id('id_log');
            $table->unsignedBigInteger('id_peminjaman');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_peminjaman_status');
    }
}
