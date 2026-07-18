<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tema di seluruh aplikasi ini pakai Bootstrap, jadi paginationnya
        // juga disamakan (default Laravel 11 pakai Tailwind).
        Paginator::useBootstrapFive();
    }
}
