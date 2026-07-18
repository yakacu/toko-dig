<h3>📂 Data Users</h3>
<a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah</a>
<table class="table table-dark table-striped">
    <thead>
        <tr><th>ID</th><th>Username</th><th>Email</th><th>Nomor Telepon</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->nomor_telepon }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $users->links() }}

<h3 class="mt-4">📂 Data Kategori</h3>
<a href="{{ route('admin.kategori.create') }}" class="btn btn-success">Tambah</a>
<table class="table table-dark table-striped">
    <thead>
        <tr><th>ID</th><th>Nama Kategori</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach ($kategoriList as $kategori)
            <tr>
                <td>{{ $kategori->id }}</td>
                <td>{{ $kategori->nama }}</td>
                <td>
                    <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $kategoriList->links() }}

<h3 class="mt-4">📂 Data Produk</h3>
<a href="{{ route('admin.produk.create') }}" class="btn btn-success">Tambah</a>
<table class="table table-dark table-striped">
    <thead>
        <tr><th>ID</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach ($produkList as $produk)
            <tr>
                <td>{{ $produk->id }}</td>
                <td>{{ $produk->nama }}</td>
                <td>{{ $produk->kategori->nama ?? '-' }}</td>
                <td>{{ number_format($produk->harga, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('admin.produk.edit', $produk) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('admin.produk.destroy', $produk) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $produkList->links() }}
