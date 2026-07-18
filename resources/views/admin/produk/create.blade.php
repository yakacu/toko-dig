@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
    <h2>Tambah Produk</h2>
    <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga (IDR)</label>
            <input type="number" step="0.01" name="harga" class="form-control" value="{{ old('harga') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <select name="stok" class="form-control" required>
                <option value="tersedia" selected>Tersedia</option>
                <option value="habis">Habis</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategoriList as $kategori)
                    <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Detail Produk</label>
            <textarea name="detail" class="form-control" rows="3" required>{{ old('detail') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Penjual</label>
            <input type="text" name="penjual_nama" class="form-control" value="{{ old('penjual_nama') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <div class="d-flex">
                <select name="kode_negara" class="form-select" style="width: 120px;" required>
                    <option value="+62" selected>+62 (ID)</option>
                    <option value="+1">+1 (US)</option>
                    <option value="+44">+44 (UK)</option>
                </select>
                <input type="text" name="whatsapp" class="form-control ms-2" placeholder="81234567890" value="{{ old('whatsapp') }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Produk</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Max 2MB</small>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
