<?php
/**
 * Location class
 * Used for providing coordinates of a city.
 */
namespace LockerHelper;

class Location implements \JsonSerializable
{
    /*
     * coordinates of Rothesay (for testing)
    const LAT = 45.3888;
    const LONG = -65.9943;
     */

    private $latitude;
    private $longitude;

    public function __construct($city = null)
    {
        // initialize with specified city or 
        // city from config if none specified
        $this->init( empty($city) ? Config::read('location.city') : $city );
    }

    public function jsonSerialize()
    {
        $json = array();
        foreach($this as $key => $value)
        {
            if (is_object($value))
            {
                $json[$key] = json_encode($value);
            }
            else
            {
                $json[$key] = $value;
            }
        }
        return $json;
    }

    public function getLatitude()
    {
    	return $this->latitude;
    }

    public function getLongitude()
    {
    	return $this->longitude;
    }

    private function init($city = null)
    {
        $this->latitude = 0;
        $this->longitude = 0;

        // use geolocation if no city specified
        if ( empty($city) )
        {
            // get lat/long of current IP address
            $url = Config::read('location.geoLocateURL');
        }
        else
        {
            // get lat/long of specified city
            $url = Config::read('location.geoCodeURL').urlencode($city);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);

        //TODO This should be implemented better, perhaps using an abstract class or interface...
        //     then implement geoLocatedLocation and geoCodedLocation that parse appropriately...
        // parse geolocation response if no city specified
        if ( !empty($response) )
        {
            if ( empty($city) )
            {
                $this->latitude = $response->location->latitude;
                $this->longitude = $response->location->longitude;
            }
            else
            {
                // parse geocode response
                $this->latitude = $response->results[0]->geometry->location->lat;
                $this->longitude = $response->results[0]->geometry->location->lng;
            }
        }
    }
}