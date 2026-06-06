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
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->enum('type', ['BIMBINGAN_HAJI', 'UMROH']);
            $table->json('fasilitas');
            $table->text('description')->nullable();

            // Store money as integer (rupiah) to avoid floating point issues.
            $table->unsignedBigInteger('harga');
            $table->unsignedBigInteger('dp');

            $table->unsignedBigInteger('minimum_pembayaran');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
