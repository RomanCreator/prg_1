<?php

namespace App\Providers;

use App\Hospital;
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
        /* Удалим все файлы связанные с моделью исследования */
        Research::deleting(function ($research) {
            if (Storage::disk('public')->exists('researches/'.$research->id)) {
                Storage::disk('public')->delete('researches/'.$research->id);

                if (Storage::disk('public')->exists('researches/'.$research->id.'.derived_300x300.png')) {
                    Storage::disk('public')->delete('researches/'.$research->id.'.derived_300x300.png');
                }
            }
        });

        /* Удалим все файлы связанные с моделью госпиталя */
        Hospital::deleting(function ($hospital) {
            if (Storage::disk('public')->exists('hospitals/'.$hospital->id)) {
                Storage::disk('public')->delete('hospitals/'.$hospital->id);

                if (Storage::disk('public')->exists('hospitals/'.$hospital->id.'.derived_300x300.png')) {
                    Storage::disk('public')->delete('hospitals/'.$hospital->id.'.derived_300x300.png');
                }
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
