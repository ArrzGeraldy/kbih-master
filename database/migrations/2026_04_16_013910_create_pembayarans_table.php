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
        Schema::create('pembayarans', function (Blueprint $table) {
             $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->enum('tipe', ['dp', 'cicilan']);

            // When using payment gateway (e.g. Midtrans), payment can start as pending,
            // so the actual paid/settled time may be unknown initially.
            $table->timestamp('tanggal_bayar')->nullable();
            $table->unsignedBigInteger('jumlah');

            $table->string('metode')->nullable();
            $table->enum('status', ['pending', 'verify', 'reject'])->default('pending');
            $table->string('bukti_path')->nullable();

            // Midtrans Snap support (optional)
            $table->string('gateway')->nullable(); // e.g. 'midtrans'
            $table->string('midtrans_order_id')->nullable();
            $table->text('snap_token')->nullable();

            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('fraud_status')->nullable();
            $table->json('gateway_payload')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
