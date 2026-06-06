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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->cascadeOnDelete();

            $table->foreignId('paket_id')
                ->constrained('pakets')
                ->cascadeOnDelete();

            $table->enum('status', ['draft', 'pending', 'active', 'done', 'cancel'])->default('draft');

            // Store money as integer (rupiah) to avoid floating point issues.
            $table->unsignedBigInteger('harga_snapshot');
            $table->unsignedBigInteger('total_tagihan');

            $table->unsignedSmallInteger('durasi_cicilan')->nullable();
            $table->unsignedBigInteger('total_dibayar')->default(0);

            $table->timestamps();

            $table->index(['user_id', 'status']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
