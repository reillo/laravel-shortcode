<?php namespace Nerweb\Shortcode;

use Illuminate\Support\ServiceProvider;

class ShortcodeServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('nerweb/shortcode');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['shortcode'] = $this->app->share(function($app)
        {
            return new Shortcode;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('shortcode');
    }
}