<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\SiteSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // No bindings to register
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('site_settings')) {
                    $view->with('S', SiteSetting::all_settings());
                } else {
                    $view->with('S', SiteSetting::defaults());
                }
            } catch (\Exception $e) {
                $view->with('S', SiteSetting::defaults());
            }
        });
    }
}
