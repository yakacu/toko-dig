@extends('layouts.admin')

@section('title', 'Admin Panel')

@section('content')
    <h2>🔹 Halo, {{ auth()->user()->username }}!</h2>
    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
    <hr>

    <form method="GET" id="admin-search-form" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ $search }}" autocomplete="off">
        <button type="submit" class="btn btn-primary mt-2">Cari</button>
        <span id="admin-search-spinner" class="spinner-border spinner-border-sm text-light ms-2 d-none"></span>
    </form>

    <div id="admin-lists">
        @include('admin.partials.lists')
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('admin-search-form');
    const searchInput = form.querySelector('input[name="search"]');
    const resultBox = document.getElementById('admin-lists');
    const spinner = document.getElementById('admin-search-spinner');
    const DEBOUNCE_MS = 400;
    let debounceTimer = null;
    let activeRequest = null;

    function loadResults(url) {
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

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => loadResults(buildUrl()), DEBOUNCE_MS);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearTimeout(debounceTimer);
        loadResults(buildUrl());
    });

    resultBox.addEventListener('click', function (e) {
        const link = e.target.closest('.pagination a');
        if (!link) return;
        e.preventDefault();
        loadResults(link.href);
    });
})();
</script>
@endpush
