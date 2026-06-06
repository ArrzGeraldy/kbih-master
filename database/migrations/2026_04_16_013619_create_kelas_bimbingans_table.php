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
        Schema::create('kelas_bimbingans', function (Blueprint $table) {
           $table->id();

            $table->foreignId('paket_id')
                ->constrained('pakets')
                ->cascadeOnDelete();

            $table->string('nama_kelas');
            $table->string('status')->default('draft');

            $table->date('mulai_periode')->nullable();
            $table->date('selesai_periode')->nullable();

            $table->string('nama_pembimbing')->nullable();

            $table->timestamps();

            $table->index(['paket_id', 'mulai_periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_bimbingans');
    }
};
