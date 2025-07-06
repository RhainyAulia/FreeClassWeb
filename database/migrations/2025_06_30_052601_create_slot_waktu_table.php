<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlotWaktuTable extends Migration
{
    public function up(): void
    {
        Schema::create('slot_waktu', function (Blueprint $table) {
            $table->id('id_slot');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_waktu');
    }
}
