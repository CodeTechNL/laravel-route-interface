<?php

    namespace CodeTech\RouteInterface\Controllers;

    use CodeTech\RouteInterface\Requests\GetRoutesRequest;
    use Illuminate\Routing\Controller;
    use Illuminate\Routing\Route;
    use Illuminate\Routing\Router;
    use Illuminate\Support\Collection;

    /**
     * Class RouteController
     * @package CodeTech\RouteInterface\Controllers
     */
    class RouteController extends Controller
    {
        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function index()
        {
            $settings = json_encode(config('route-interface.interface', []));

            return view('route-interface::master', compact('settings'));
        }

        /**
         * @param GetRoutesRequest $request
         * @param Router $router
         * @return array
         */
        public function indexJson(GetRoutesRequest $request, Router $router)
        {
            /** @var Collection $list */
            $list = collect([]);
            /** @var Route $route */
            foreach ($router->getRoutes()->getRoutes() as $route) {

                $list->push([
                    'name'             => $route->getName(),
                    'domain'           => $route->getDomain(),
                    'action_method'    => $route->getActionMethod(),
                    'methods'          => $route->methods(),
                    'uri'              => $route->uri(),
                    'middleware'       => $route->getAction('middleware'),
                    'controller'       => $route->getAction('controller'),
                    'prefix'           => $route->getAction('prefix'),
                    'wheres'           => $route->wheres,
                    'route_parameters' => $route->parameterNames()
                ]);
            }

            return $list
                ->sortBy($request->getSort(), SORT_REGULAR, $request->getDescending())
                ->when($request->getSearchTerm(), function (Collection $q) use ($request) {

                    return $q->filter(function ($item) use ($request) {
                        $data = $this->makeSimpleString($item[$request->getSearchColumn()]);

                        $terms = explode('+', $request->getSearchTerm());

                        if ($data) {
                            foreach ($terms as $term) {
                                if (str_contains($data, $term)) {
                                    return $item;
                                }
                            }
                        }


                        return false;
                    });
                })
                ->values()
                ->all();
        }

        /**
         * Convert array to string and lowerCase the value
         *
         * @param $data
         * @return string
         */
        private function makeSimpleString($data)
        {
            if (is_array($data)) {
                $data = implode(',', $data);
            }

            if ($data) {
                return strtolower($data);
            }

            return '';
        }
    }
