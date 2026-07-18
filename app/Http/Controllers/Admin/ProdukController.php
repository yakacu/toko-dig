<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function create()
    {
        $kategoriList = Kategori::cachedList();

        return view('admin.produk.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Produk $produk)
    {
        $kategoriList = Kategori::cachedList();

        return view('admin.produk.edit', compact('produk', 'kategoriList'));
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');

            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
        }

        $produk->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dihapus!');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|in:tersedia,habis',
            'kategori_id' => 'required|exists:kategori,id',
            'detail' => 'required|string',
            'penjual_nama' => 'required|string|max:255',
            'kode_negara' => 'required|string|max:5',
            'whatsapp' => 'required|digits_between:7,15',
            'foto' => 'nullable|image|max:2048',
        ]);

        $validated['whatsapp'] = $validated['kode_negara'] . $validated['whatsapp'];
        unset($validated['kode_negara']);

        return $validated;
    }
}
