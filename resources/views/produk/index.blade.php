@extends('layouts.shop')

@section('title', 'Beranda - Toko Online')

@section('content')
    <h2 class="mb-4">🛍️ Kategori Produk</h2>
    <div class="row">
        @foreach ($kategoriList as $kategori)
            <div class="col-md-3">
                <a href="{{ route('produk.search', ['category' => $kategori->id]) }}" class="btn btn-outline-primary btn-block mb-3">
                    {{ $kategori->nama }}
                </a>
            </div>
        @endforeach
    </div>

    <h2 class="mt-5 mb-4">🔥 Produk Terbaru</h2>
    <div class="row">
        @forelse ($produkTerbaru as $produk)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="{{ $produk->fotoUrl() }}" class="card-img-top" alt="{{ $produk->nama }}" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $produk->nama }}</h5>
                        <p class="text-muted"><span class="badge bg-primary">{{ $produk->kategori->nama ?? '-' }}</span></p>
                        <h6 class="text-primary">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h6>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('produk.show', $produk) }}" class="btn btn-primary">Lihat detail</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada produk.</p>
        @endforelse
    </div>
@endsection
