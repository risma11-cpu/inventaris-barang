<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EoqController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('beranda');

Route::resource('barang', BarangController::class)->except(['show']);

Route::resource('transaksi', TransaksiController::class)->only(['index', 'create', 'store', 'destroy']);

Route::get('/eoq', [EoqController::class, 'index'])->name('eoq.index');
Route::get('/eoq/cetak', [EoqController::class, 'cetak'])->name('eoq.cetak');
Route::get('/eoq/ekspor', [EoqController::class, 'ekspor'])->name('eoq.ekspor');