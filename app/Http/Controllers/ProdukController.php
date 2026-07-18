<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $kategoriList = Kategori::cachedList();
        $produkTerbaru = Produk::cachedTerbaru(6);

        return view('produk.index', compact('kategoriList', 'produkTerbaru'));
    }

    public function search(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $category = (int) $request->query('category', 0);

        $kategoriList = Kategori::cachedList();

        $produkQuery = Produk::with('kategori')
            ->where('stok', 'tersedia')
            ->when($search !== '', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
            ->when($category > 0, fn ($q) => $q->where('kategori_id', $category));

        $produkList = $produkQuery->paginate(9)->withQueryString();

        // Dipanggil lewat fetch() oleh JS debounce di produk/search.blade.php,
        // jadi cukup balikin fragment HTML-nya saja, tidak perlu layout penuh.
        if ($request->ajax()) {
            return view('produk.partials.list', compact('produkList'));
        }

        return view('produk.search', compact('kategoriList', 'produkList', 'search', 'category'));
    }

    public function show(Produk $produk)
    {
        $produk->load('kategori');

        return view('produk.show', compact('produk'));
    }
}
