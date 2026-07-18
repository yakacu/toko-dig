@extends('layouts.shop')

@section('title', 'Search Toko Online')

@section('content')
    <form method="GET" id="search-form" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ $search }}" autocomplete="off">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-control">
                    <option value="0">Semua Kategori</option>
                    @foreach ($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}" @selected($category == $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Cari</button>
                <span id="search-spinner" class="spinner-border spinner-border-sm text-light ms-2 d-none"></span>
            </div>
        </div>
    </form>

    <h3 class="mb-4">📦 Produk</h3>

    <div id="produk-list">
        @include('produk.partials.list')
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('search-form');
    const searchInput = form.querySelector('input[name="search"]');
    const categorySelect = form.querySelector('select[name="category"]');
    const resultBox = document.getElementById('produk-list');
    const spinner = document.getElementById('search-spinner');
    const DEBOUNCE_MS = 400;
    let debounceTimer = null;
    let activeRequest = null;

    function loadResults(url) {
        // Batalkan request sebelumnya kalau masih menggantung, supaya hasil
        // yang lama tidak menimpa hasil yang lebih baru (race condition).
        if (activeRequest) activeRequest.abort();
        activeRequest = new AbortController();

        spinner.classList.remove('d-none');

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: activeRequest.signal,
        })
            .then((res) => res.text())
            .then((html) => {
                resultBox.innerHTML = html;
                window.history.replaceState({}, '', url);
            })
            .catch((err) => {
                if (err.name !== 'AbortError') console.error(err);
            })
            .finally(() => spinner.classList.add('d-none'));
    }

    function buildUrl() {
        const params = new URLSearchParams(new FormData(form)).toString();
        return `${form.action || window.location.pathname}?${params}`;
    }

    function debouncedSearch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => loadResults(buildUrl()), DEBOUNCE_MS);
    }

    searchInput.addEventListener('input', debouncedSearch);
    categorySelect.addEventListener('change', () => loadResults(buildUrl()));

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearTimeout(debounceTimer);
        loadResults(buildUrl());
    });

    // Event delegation supaya link pagination tetap jalan walau kontennya
    // sudah diganti lewat innerHTML.
    resultBox.addEventListener('click', function (e) {
        const link = e.target.closest('.pagination a');
        if (!link) return;
        e.preventDefault();
        loadResults(link.href);
    });
})();
</script>
@endpush
