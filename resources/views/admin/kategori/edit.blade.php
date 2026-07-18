@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <h2>Edit Kategori</h2>
    <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $kategori->nama) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
