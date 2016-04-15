<?php
return [
    /**
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    //'debug' => filter_var((isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] : false), FILTER_VALIDATE_BOOLEAN),
    'debug' => true,

    /**
     * Time configuration.
     *
     * format - specify formatting of the time display
     */
    'Time' => [
        'format' => 'h\uee01mm\nEEEE\nMMMM d',
    ],

    /**
     * Location configuration.
     *
     * city - if specified, will use the city (instead of geolocation by IP address)
     * geoURL - URL of service to use for IP address geolocation
     */
    'Location' => [
        //'city' => 'Rothesay, Canada',
        'city' => null,
        'geoLocateURL' => 'http://geoip.nekudo.com/api/',
        'geoCodeURL' => 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=',
    ],

    /**
     * Weather configuration.
     *
     * apikey - API key required by weather service
     * options - options to be passed to weather service
     */
    'Weather' => [
        'apikey' => 'YOUR_FORECAST.IO_KEY',
        'options' => [
            'units' => 'ca',
            'exclude' => 'flags',
        ],
    ],

    /**
     * Quote configuration.
     *
     */
    'Quote' => [
    ],

    /**
     * Schedule configuration.
     *
     */
    'Schedule' => [
    ],

    /**
     * Announcements configuration.
     *
     */
    'Announcements' => [
    ],

    /**
     * Assignments configuration.
     *
     */
    'Assignments' => [
    ],

    /**
     * Twitter configuration.
     *
     * key - key required for twitter OAuth
     * secret - secret required for twitter OAuth
     */
    'Twitter' => [
        'key' => 'YOUR_TWITTER_KEY',
        'secret' => 'YOUR_TWITTER_SECRET',
    ],
];