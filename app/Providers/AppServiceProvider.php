<?php

namespace App\Providers;

use App\Research;
use Illuminate\Support\ServiceProvider;
use Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Research::deleting(function ($research) {
            if (Storage::disk('public')->exists('researces/'.$research->id)) {
                Storage::disk('public')->delete('researches/'.$research->id);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
