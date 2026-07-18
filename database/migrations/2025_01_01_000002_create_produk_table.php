<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->nullOnDelete();
            $table->string('penjual_nama');
            $table->string('whatsapp');
            $table->string('nama')->nullable()->index();
            $table->double('harga')->nullable();
            $table->string('foto')->nullable();
            $table->text('detail')->nullable();
            $table->enum('stok', ['habis', 'tersedia'])->default('tersedia');
            $table->timestamps();

            $table->index(['stok', 'kategori_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
