<?php

namespace App\Services\Deployment;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DeploymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    protected $namespace = 'App\Services\Deployment';

    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     * commit
     * @return void
     */
    public function register()
    {
        Route::group([
            'prefix'        => 'api/server',
            'namespace'     => $this->namespace,
            'middleware'    => 'api'
        ], function($router){
            $router->post('/github_hook', 'DeploymentController@hook')->name('api.server.github_hook');
        });
    }
}
