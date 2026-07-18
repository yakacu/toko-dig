<div class="row">
    @forelse ($produkList as $produk)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="{{ $produk->fotoUrl() }}" class="card-img-top" alt="{{ $produk->nama }}" style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $produk->nama }}</h5>
                    <p class="text-muted"><span class="badge bg-primary">{{ $produk->kategori->nama ?? '-' }}</span></p>
                    <h6 class="text-primary">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h6>
                    <p class="text-muted"><span class="badge bg-warning"><b style="color:gray;">{{ $produk->penjual_nama }}</b></span></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="{{ route('produk.show', $produk) }}" class="btn btn-primary">Lihat detail</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p>Produk tidak ditemukan.</p>
    @endforelse
</div>

{{ $produkList->links() }}
