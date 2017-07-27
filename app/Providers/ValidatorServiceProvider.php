<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        /**
         * extend validator
         */
        Validator::extend('required_if_attribute', function ($attribute, $value, $parameters, $validator) {
            $required = false;
        
            switch ($parameters[1]) {
                case '==':
                    $required = $validator->attributes()[$parameters[0]] == $parameters[2];
                case '!=':
                    $required = $validator->attributes()[$parameters[0]] != $parameters[2];
                case '===':
                    $required = $validator->attributes()[$parameters[0]] === $parameters[2];
                case '!==':
                    $required = $validator->attributes()[$parameters[0]] !== $parameters[2];
                case '<':
                    $required = $validator->attributes()[$parameters[0]] < $parameters[2];
                case '<=':
                    $required = $validator->attributes()[$parameters[0]] <= $parameters[2];
                case '>':
                    $required = $validator->attributes()[$parameters[0]] > $parameters[2];
                case '>=':
                    $required = $validator->attributes()[$parameters[0]] >= $parameters[2];
            }
             
            return $required ? $validator->validate_required($attribute, $value) : true;
        });
        
        Validator::extend('equal_before', function ($attribute, $value, $parameters, $validator) {
            if (!empty($value) && !empty($validator->attributes()[$parameters[0]])) {
                return strtotime($value) <= strtotime($validator->attributes()[$parameters[0]]);
            }
            
            return true;
        });
        
        Validator::extend('equal_after', function ($attribute, $value, $parameters, $validator) {
            if (!empty($value) && !empty($validator->attributes()[$parameters[0]])) {
                return strtotime($value) >= strtotime($validator->attributes()[$parameters[0]]);
            }
            
            return true;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        return;
    }
}
