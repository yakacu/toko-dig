@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <h2>Edit User</h2>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kode Negara</label>
            <select name="kode_negara" class="form-control" required>
                <option value="+62" @selected(str_starts_with($user->nomor_telepon, '+62'))>Indonesia (+62)</option>
                <option value="+1" @selected(str_starts_with($user->nomor_telepon, '+1'))>USA (+1)</option>
                <option value="+44" @selected(str_starts_with($user->nomor_telepon, '+44'))>UK (+44)</option>
                <option value="+91" @selected(str_starts_with($user->nomor_telepon, '+91'))>India (+91)</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" class="form-control" pattern="[1-9][0-9]{6,14}" title="Masukkan hanya angka (7-15 digit) tanpa awalan 0" value="{{ old('nomor_telepon', preg_replace('/^\+\d{1,3}/', '', $user->nomor_telepon)) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
