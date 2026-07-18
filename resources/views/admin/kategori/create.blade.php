@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
    <h2>Tambah Kategori</h2>
    <form method="POST" action="{{ route('admin.kategori.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
