<?php
namespace OliveMedia\OliveFacade;

use Illuminate\Support\ServiceProvider;
use OliveMedia\OliveFacade\slug\Slug;

class OliveFacadeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('UniqueSlug', function($app)
        {
            return new Slug(new \OliveMedia\OliveFacade\slug\SlugRepository);
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
