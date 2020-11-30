<?php

    return [
        /**
         * Define here your own middleware
         */
        'middleware' => [],

        /**
         * Change the route prefix and/or suffix. Default: yourdomain.com/development/routes
         */
        'url'        => [
            'prefix' => 'development',
            'suffix' => 'routes'
        ],

        /**
         * Interface settings
         */
        'interface'  => [

            /**
             * When the search term is changed, wait x amount of time to load the new results
             */
            'keyup_time_rate' => 2500,

            /**
             * Polling will get data every x amount of seconds. You can disable it by setting enabled to false
             */
            'polling' => [
                'enabled' => true,
                'refresh_rate' => 2500
            ]
        ]
    ];
