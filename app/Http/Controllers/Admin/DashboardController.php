<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $like = "%{$search}%";

        $users = User::where('username', 'like', $like)
            ->orWhere('email', 'like', $like)
            ->paginate(10, ['*'], 'users_page');

        $kategoriList = Kategori::where('nama', 'like', $like)
            ->paginate(10, ['*'], 'kategori_page');

        $produkList = Produk::with('kategori')
            ->where('nama', 'like', $like)
            ->paginate(10, ['*'], 'produk_page');

        // Dipanggil lewat fetch() oleh JS debounce di admin/dashboard.blade.php,
        // jadi cukup balikin fragment HTML 3 tabelnya saja.
        if ($request->ajax()) {
            return view('admin.partials.lists', compact('users', 'kategoriList', 'produkList'));
        }

        return view('admin.dashboard', compact('users', 'kategoriList', 'produkList', 'search'));
    }
}
