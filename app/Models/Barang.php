<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'stok',
        'harga_satuan',
        'biaya_pemesanan',
        'biaya_penyimpanan',
        'lead_time',
        'safety_stock',
    ];

    protected $casts = [
        'stok' => 'integer',
        'harga_satuan' => 'float',
        'biaya_pemesanan' => 'float',
        'biaya_penyimpanan' => 'float',
        'lead_time' => 'integer',
        'safety_stock' => 'integer',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}