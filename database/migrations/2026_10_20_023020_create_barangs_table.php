<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 30)->unique();
            $table->string('nama_barang');
            $table->string('satuan', 20);
            $table->unsignedInteger('stok')->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('biaya_pemesanan', 15, 2)->default(0);   // S: biaya per sekali pesan
            $table->decimal('biaya_penyimpanan', 15, 2)->default(0); // H: biaya simpan per unit per tahun
            $table->unsignedSmallInteger('lead_time')->default(1);   // hari
            $table->unsignedInteger('safety_stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};