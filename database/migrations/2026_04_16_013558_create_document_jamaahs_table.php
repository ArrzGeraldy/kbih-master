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
        Schema::create('document_jamaahs', function (Blueprint $table) {
             $table->id();

            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->cascadeOnDelete();

            $table->string('jenis');
            $table->string('file_path');

            $table->enum('status', ['proses', 'reject', 'verify'])->default('proses');
            $table->text('alasan_penolakan')->nullable();

            $table->timestamp('verify_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->unique(['jamaah_id', 'jenis']);
            $table->index(['jamaah_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_jamaahs');
    }
};
