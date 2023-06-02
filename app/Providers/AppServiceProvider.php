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
        $models = [
            'Auth',
            'Restaurant',
        ];
        foreach ($models as $model)
            app()->bind(
                'App\Services\\' . $model . 'ServiceInterface',
                'App\Services\\' . $model . 'Service'
            );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
