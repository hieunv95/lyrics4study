<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('showErrClass', 'components.form.error_class', ['name', 'value', 'attributes']);
        Form::component('showErrField', 'components.form.error_field', ['name', 'value', 'attributes']);

        Validator::extend('available', function ($attribute, $value, $parameters, $validator) {
            return $value !== false;
        });
        Validator::extend('is_srt_format', function ($attribute, $value, $parameters, $validator) {

            return isSRTFormat(file($value)) === true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register('\Laracasts\Generators\GeneratorsServiceProvider');
            $this->app->register('\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }
    }
}
