<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $elektronik = Kategori::create(['nama' => 'Elektronik']);
        $service = Kategori::create(['nama' => 'Service']);

        Produk::create([
            'kategori_id' => $elektronik->id,
            'penjual_nama' => 'NW-Store',
            'whatsapp' => '+62831012231',
            'nama' => 'Laptop Asus ROG',
            'harga' => 6000000,
            'foto' => 'produk/1741853131_Logo.png',
            'detail' => 'Blablabla',
            'stok' => 'tersedia',
        ]);

        Produk::create([
            'kategori_id' => $service->id,
            'penjual_nama' => 'Wolf.Dev',
            'whatsapp' => '+6283101214581',
            'nama' => 'Jasa Developer',
            'harga' => 50000,
            'foto' => 'produk/1741909309_serigala.jpg',
            'detail' => 'Dicoba dulu',
            'stok' => 'tersedia',
        ]);

        // Akun admin default untuk login, silakan ganti setelah login pertama kali
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'nomor_telepon' => '+6280000000000',
            'password' => Hash::make('password'),
        ]);
    }
}
