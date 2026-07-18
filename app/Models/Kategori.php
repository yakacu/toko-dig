<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = ['nama'];

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }

    /**
     * Daftar kategori jarang berubah, jadi di-cache 1 jam.
     * Cache otomatis dibersihkan lewat event saved/deleted di bawah.
     */
    public static function cachedList()
    {
        return Cache::remember('kategori.all', 3600, fn () => static::orderBy('nama')->get());
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('kategori.all'));
        static::deleted(fn () => Cache::forget('kategori.all'));
    }
}
