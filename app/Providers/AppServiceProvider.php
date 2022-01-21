<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // storage/logs/laravel.logでクエリーログを出す設定
        if (config('app.env') !== 'production') {
            \DB::listen(function ($query) {
                \Log::info("[Query Time:{$query->time}s] $query->sql");
            });
        }
    }
}
