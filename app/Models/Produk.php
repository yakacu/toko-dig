<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori_id',
        'penjual_nama',
        'whatsapp',
        'nama',
        'harga',
        'foto',
        'detail',
        'stok',
    ];

    protected $casts = [
        'harga' => 'double',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function fotoUrl(): string
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('img/no-image.png');
    }

    public function waLink(): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $this->whatsapp);
        $pesan = urlencode("Halo, saya tertarik dengan produk \"{$this->nama}\" yang Anda jual. Bisa saya dapatkan informasi lebih lanjut?");

        return "https://wa.me/{$nomor}?text={$pesan}";
    }

    /**
     * Produk terbaru untuk beranda, di-cache singkat (5 menit) karena
     * halaman ini paling sering diakses. Cache otomatis dibersihkan
     * lewat event saved/deleted di bawah setiap kali ada perubahan produk.
     */
    public static function cachedTerbaru(int $limit = 6)
    {
        return Cache::remember("produk.terbaru.{$limit}", 300, function () use ($limit) {
            return static::with('kategori')
                ->where('stok', 'tersedia')
                ->latest('id')
                ->limit($limit)
                ->get();
        });
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('produk.terbaru.6'));
        static::deleted(fn () => Cache::forget('produk.terbaru.6'));
    }
}
