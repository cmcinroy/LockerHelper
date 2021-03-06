<?php
/**
 * Weather class
 * Used for providing weather data.
 */
namespace LockerHelper;

// Guilherme Uhelski's PHP wrapper for the Forcast.io API
use Forecast\Forecast;

class Weather implements \JsonSerializable
{
	const IMG_PRECIP = 'umbrella';
	const TEMP_SUFFIX = '&deg;';
	const PRECIP_SUFFIX = '&#37;';

    private $location;
	private $forecast;
	private $current_temp;
	private $current_precip;
	private $img_current_icon;
	private $day_summary;
	private $day_precip;
	private $img_day_icon;
	private $img_precip_icon;

    public function __construct()
    {
		// get lat/long for weather source
		$this->location = new Location();
		// get forecast
		$this->forecast = new Forecast(Config::read('weather.apikey'));

		// update vars
        $this->update();
    }

    // render the object as a JSON string
    public function jsonSerialize()
    {
		$json = array();
    	foreach($this as $key => $value)
    	{
        	if ( !is_object($value) && ($value != null) )
        	{
        		$json[$key] = $value;
        	}
    	}
    	return $json;
    }

    // return html representation of object
    public function render()
    {
    	return json_encode($this);
    }


    // refresh values 
    public function update()
    {
    	try {
			$response = $this->forecast->get($this->location->getLatitude(), $this->location->getLongitude(), null, Config::read('weather.options'));
		    if ( empty($response->currently)) {
		    	throw new \Exception('Forecast: weather API unavailable');
		    } else {
				// current info
				$current = $response->currently;
				$this->current_temp = strval( round( (float) $current->temperature ) ) . self::TEMP_SUFFIX;
				$this->current_precip = round( 100 * (float) $current->precipProbability );
				$this->img_current_icon = $current->icon;
		    }
			if ( !empty($response->hourly) ) {
				// daily info
				$day = $response->hourly;
				$this->day_summary = $day->summary;
				// calculate average over the whole day
				foreach ( $day->data as $datum )
				{
					$this->day_precip += (float) $datum->precipProbability;
				}
				$this->day_precip = strval( round( 100 * $this->day_precip / count($day->data) ) ) . self::PRECIP_SUFFIX;
				$this->img_day_icon = $day->icon;

		    	// handle constants
		    	$this->img_precip_icon = self::IMG_PRECIP;
		    }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            return false;
        }
    }

}