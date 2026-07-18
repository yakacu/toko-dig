<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'kode_negara' => 'required|string|max:5',
            'nomor_telepon' => ['required', 'regex:/^[1-9][0-9]{6,14}$/'],
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['kode_negara'] . $validated['nomor_telepon'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'kode_negara' => 'required|string|max:5',
            'nomor_telepon' => ['required', 'regex:/^[1-9][0-9]{6,14}$/'],
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['kode_negara'] . $validated['nomor_telepon'],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil dihapus!');
    }
}
