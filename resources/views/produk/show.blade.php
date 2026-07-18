@extends('layouts.shop')

@section('title', 'Detail Produk - ' . $produk->nama)

@section('content')
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="{{ $produk->fotoUrl() }}" class="img-fluid rounded-start" alt="{{ $produk->nama }}">
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <h3 class="card-title">{{ $produk->nama }}</h3>
                    <p class="text-muted"><span class="badge bg-primary">{{ $produk->kategori->nama ?? '-' }}</span></p>
                    <h4 class="text-primary">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h4>
                    <p class="card-text mt-3">{!! nl2br(e($produk->detail)) !!}</p>
                    <p class="card-text"><span class="badge bg-warning"><b style="color:gray;">{{ $produk->penjual_nama }}</b></span></p>
                    <a href="{{ $produk->waLink() }}" class="btn btn-success" target="_blank">Hubungi Penjual via WhatsApp</a>
                    <a href="{{ route('produk.search') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
