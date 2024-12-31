<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\modules;
use App\Models\menus;


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
        $menuData = menus::where('id', 1)->first();
        $module = modules::get();
        if ($menuData) {
            $menuData->json_output = json_decode($menuData->json_output, true);
        }
        // \Log::debug('Menu Data: ', ['menu' => $menuData]);
        View::share(['menu'=> $menuData,'module'=>$module]);
    }
    
}
