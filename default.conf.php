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
    'debug' => filter_var((isset($_ENV['DEBUG']) ? $_ENV['DEBUG'] : false), FILTER_VALIDATE_BOOLEAN),

    /**
     * Minimum refresh interval (in milliseconds).
     * This is also a multiplier for component intervals.
     * e.g. 'checkInterval' => 60000 will perform refresh check every minute
     *       + Weather.interval => 15 will update weather every 15mins
     * Component interval can be set to -1 to disable component
     */
    'checkInterval' => 60000,

    /**
     * Time configuration.
     *
     * dateFormat - specify date display formatting
     * timeFormat - specify time display formatting
     */
    'time' => [
        // dateFormat for Mirror layout is 'dddd<br/>MMMM DD'
        'dateFormat' => 'dddd, MMMM DD',
        'timeFormat' => 'h:mm',
    ],

    /**
     * Version configuration.
     *
     * refspec - git branch/tag
     */
    'version' => [
        'refspec' => 'HEAD',
    ],

    /**
     * Location configuration.
     *
     * city - if specified, will use the city (instead of geolocation by IP address)
     * geoLocateURL - URL of service to use for IP address geolocation
     * geoCodeURL - URL of service to use for city geocoding
     */
    'location' => [
        //'city' => 'Rothesay, Canada',
        'city' => null,
        'geoLocateURL' => 'http://geoip.nekudo.com/api/',
        'geoCodeURL' => 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=',
    ],

    /**
     * Weather configuration.
     *
     * interval - number of checkIntervals to wait before refresh (-1 = disable)
     * apikey - API key required by weather service
     * options - options to be passed to weather service
     */
    'weather' => [
        'interval' => 15,
        'apikey' => 'YOUR_FORECAST.IO_KEY',
        'options' => [
            'units' => 'ca',
            'exclude' => 'flags',
        ],
    ],

    /**
     * Quote configuration.
     *
     * interval - number of checkIntervals to wait before refresh (-1 = disable)
     * URL - URL of the Quote RSS feed
     * symbol - fontawesome symbol name
     */
    'quotes' => [
        'interval' => 30,
        'URL' => 'http://feeds.feedburner.com/brainyquote/QUOTEBR',
        // Art Quote of the Day
        // 'URL' => 'http://feeds.feedburner.com/brainyquote/QUOTEAR',
        'symbol' => 'fa-comment-o', 
    ],

    /**
     * Agenda/schedule/timetable configuration.
     *
     * interval - number of checkIntervals to wait before refresh (-1 = disable)
     * authConfig - relativce path to Google API Service Account key auth config file
     * key - id of the Google Sheet
     * student - name of the student to lookup on the first sheet, to determine class 
     * class - worksheet that has the appropriate class timetable
     * symbol - fontawesome symbol name
     */
    'agenda' => [
        'interval' => 5,
        'authConfig' => 'YOUR_GOOGLE_API_SERVICE_ACCOUNT_KEY_AUTHCONFIG_FILE',
        'key' => 'YOUR_GOOGLE_SHEET_KEY',
        'class' => '7-CR',
        'symbol' => 'fa-calendar', 
    ],

    /**
     * Announcements configuration.
     *
     * interval - number of checkIntervals to wait before refresh (-1 = disable)
     * URL - URL of the school home page
     * symbol - fontawesome symbol name
     */
    'announcements' => [
        'interval' => 60,
        'URL' => 'YOUR_SCHOOL_HOME_PAGE',
        'symbol' => 'fa-bullhorn', 
    ],

    /**
     * Calendar events (Assignments) configuration.
     *
     * interval - number of checkIntervals to wait before refresh (-1 = disable)
     * key - Google Calendar API key
     * id - id of a public Google Calendar
     * symbol - fontawesome symbol name
    */
    'events' => [
        'interval' => 10,
        'key' => 'YOUR_GOOGLE_API_KEY',
        'id' => 'YOUR_GOOGLE_CALENDAR_ID',
        'symbol' => 'fa-check-square-o', 
    ],

    /**
     * Twitter configuration.
     *
     * interval - number of checkIntervals to wait before refresh (-1 = disable)
     * username - Twitter screen name of the feed to be read
     * count - Number of tweets to read
     * feed - Twitter API URL
     * key - key required for twitter OAuth
     * secret - secret required for twitter OAuth
     * symbol - fontawesome symbol name
     */
    'tweets' => [
        'interval' => 30,
        'username' => 'YOUR_SCHOOL_TWITTER_HANDLE',
        'count' => 4,
        'feed' => 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name={$username}&count={$count}&include_rts=1',
        'key' => 'YOUR_TWITTER_KEY',
        'secret' => 'YOUR_TWITTER_SECRET',
        'bearer' => 'YOUR_TWITTER_BEARER_TOKEN',
        'symbol' => 'fa-twitter', 
    ],

    /**
     * News configuration.
     *
     * interval - number of checkIntervals to wait before refresh (-1 = disable)
      * URL - URL of the news RSS feed
    * symbol - fontawesome symbol name
     */
    'news' => [
        'interval' => -1,
        // Associated Press Top Headlines
        'URL' => 'http://hosted2.ap.org/atom/APDEFAULT/3d281c11a96b4ad082fe88aa0db04305',
        'symbol' => 'fa-newspaper-o', 
    ],
];