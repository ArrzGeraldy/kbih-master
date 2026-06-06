<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sesi_bimbingans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelas_bimbingan_id')
                ->constrained('kelas_bimbingans')
                ->cascadeOnDelete();

            $table->string('judul');
            $table->timestamp('mulai_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->text('lokasi')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->index(['kelas_bimbingan_id', 'mulai_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_bimbingans');
    }
};
