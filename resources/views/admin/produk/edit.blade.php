@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <h2>Edit Produk</h2>
    <form method="POST" action="{{ route('admin.produk.update', $produk) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $produk->nama) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga (IDR)</label>
            <input type="number" step="0.01" name="harga" class="form-control" value="{{ old('harga', $produk->harga) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <select name="stok" class="form-control" required>
                <option value="tersedia" @selected($produk->stok == 'tersedia')>Tersedia</option>
                <option value="habis" @selected($produk->stok == 'habis')>Habis</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategoriList as $kategori)
                    <option value="{{ $kategori->id }}" @selected($produk->kategori_id == $kategori->id)>{{ $kategori->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Detail Produk</label>
            <textarea name="detail" class="form-control" rows="3" required>{{ old('detail', $produk->detail) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Penjual</label>
            <input type="text" name="penjual_nama" class="form-control" value="{{ old('penjual_nama', $produk->penjual_nama) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <div class="d-flex">
                <select name="kode_negara" class="form-select" style="width: 120px;" required>
                    <option value="+62" @selected(str_starts_with($produk->whatsapp, '+62'))>+62 (ID)</option>
                    <option value="+1" @selected(str_starts_with($produk->whatsapp, '+1'))>+1 (US)</option>
                    <option value="+44" @selected(str_starts_with($produk->whatsapp, '+44'))>+44 (UK)</option>
                </select>
                <input type="text" name="whatsapp" class="form-control ms-2" value="{{ old('whatsapp', preg_replace('/^\+\d{1,3}/', '', $produk->whatsapp)) }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Produk</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
            @if ($produk->foto)
                <img src="{{ $produk->fotoUrl() }}" width="100" class="mt-2">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
