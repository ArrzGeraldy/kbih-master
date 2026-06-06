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
        Schema::create('order_umroh_details', function (Blueprint $table) {
            $table->foreignId('order_id')
                ->primary()
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->date('tanggal_keberangkatan')->nullable();

            $table->foreignId('kelas_bimbingan_id')
                ->nullable()
                ->constrained('kelas_bimbingans')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_umroh_details');
    }
};
