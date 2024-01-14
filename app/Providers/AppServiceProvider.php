<?php

namespace App\Providers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        CompanyResource::withoutWrapping();
        CategoryResource::withoutWrapping();
    }
}
