<?php
/**
 * Created by HoanXuanMai
 * Email: hoanxuanmai@gmail.com
 */

namespace HXM2FA;

use \Illuminate\Support\ServiceProvider as Service;

class ServiceProvider extends Service
{
    public function boot()
    {
        if (\config('hxm2fa') && !\config('hxm2fa.enabled'))
            return;

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'HXM2FA');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'HXM2FA');

        $this->publishes([__DIR__.'/../config/hxm2fa.php' => config_path('hxm2fa.php')], 'hxm2fa');

        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/hxm2fa')], 'hxm2fa');

        $this->app->register(RouteServiceProvider::class);
    }

    public function register()
    {
        if (\config('hxm2fa') && !\config('hxm2fa.enabled'))
            return;

        $this->mergeConfigFrom(__DIR__.'/../config/hxm2fa.php', 'hxm2fa');

        $this->app->singleton(HXM2FA::class, function(){
            return new HXM2FA();
        });

    }

}
