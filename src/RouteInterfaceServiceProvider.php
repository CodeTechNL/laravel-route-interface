<?php

    namespace CodeTech\RouteInterface;

    use Illuminate\Routing\Router;
    use Illuminate\Support\ServiceProvider;

    /**
     * Class RouteInterfaceServiceProvider
     * @package CodeTech\RouteInterface
     */
    class RouteInterfaceServiceProvider extends ServiceProvider
    {
        /**
         * @param Router $router
         */
        public function boot(Router $router)
        {
            $this->registerConfig();
            $this->registerRoutes($router);
            $this->registerAssets();
        }

        /**
         *
         */
        private function registerConfig()
        {
            $configPath = __DIR__ . '/../config/route-interface.php';

            $this->publishes([
               $configPath => config_path('route-interface.php'),
            ], 'route-interface');

            $this->mergeConfigFrom($configPath, 'route-interface');
        }

        /**
         * @param $router
         */
        private function registerRoutes($router)
        {
            $router->group([
                'prefix'     => $this->getConf('url.prefix'),
                'namespace'  => 'CodeTech\\RouteInterface\\Controllers',
                'middleware' => $this->getConf('middleware')
            ], function (Router $router) {
                $router->get($this->getConf('url.suffix'), 'RouteController@index');
                $router->get($this->getConf('url.suffix') . '.json', 'RouteController@indexJson');
            });
        }

        /**
         * @param $key
         * @return mixed
         */
        public function getConf($key)
        {
            return config()->get('route-interface.' . $key);
        }

        /**
         *
         */
        private function registerAssets()
        {
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/route-interface'),
            ], 'route-interface');
        }

        /**
         *
         */
        public function register()
        {
            $this->loadViewsFrom(__DIR__ . '/resources/views/', 'route-interface');
        }

        /**
         * @return string
         */
        protected function getConfigPath()
        {
            return config_path('route-interface.php');
        }
    }
