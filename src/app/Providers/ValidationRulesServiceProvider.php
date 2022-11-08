<?php

namespace App\Providers;

use App\Rules\Telegram\SupportedUrl;
use App\Rules\Telegram\ValidEncodedImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationRulesServiceProvider extends ServiceProvider
{
    protected array $rules = [
        'supported_url' => SupportedUrl::class,
        'valid_image'   => ValidEncodedImage::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->rules as $alias => $rule) {
            Validator::extend($alias, $rule . '@passes');
        }
    }
}
