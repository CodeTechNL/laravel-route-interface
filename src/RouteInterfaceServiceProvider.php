<?php

    namespace CodeTech\RouteInterface;

    use Illuminate\Routing\Router;
    use Illuminate\Support\ServiceProvider;

    class RouteInterfaceServiceProvider extends ServiceProvider
    {
        public function boot(Router $router)
        {
            $router->group([
                'prefix' => 'development',
                'namespace' => 'CodeTech\\RouteInterface\\Controllers'
            ], function(Router $router){
                $router->get('routes', 'RouteController@index');
                $router->get('routes.json', 'RouteController@indexJson');
            });

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/route-interface'),
            ], 'route-interface');
        }

        public function register()
        {
            $this->loadViewsFrom(__DIR__.'/resources/views/', 'route-interface');
        }
    }
