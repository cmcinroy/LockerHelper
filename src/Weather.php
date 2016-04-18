<?php
/**
 * Weather class
 * Used for providing weather data.
 */
namespace LockerHelper;

use Forecast\Forecast;

class Weather extends DataUpdater
{
    private $location;
	private $forecast;
	private $current_temp;
	private $current_precip;
	private $img_current_icon;
	private $day_summary;
	private $day_precip;
	private $img_day_icon;

    public function __construct()
    {
		// get lat/long for weather source
		$this->location = new Location();
		// get forecast
		$this->forecast = new Forecast(Config::read('Weather.apikey'));

		// update vars
        $this->update();
    }

    // return html representation of object
    public function render()
    {
    	//TODO render based on Reflection
    	//var_dump($this);
    	$reflector = new \ReflectionClass($this);
        var_dump($reflector->getDefaultProperties());
		return parent::render();
    }

    // refresh values 
    public function update()
    {
		//$response = $this->forecast->get($this->location->getLatitude(), $this->location->getLongitude(), NULL, Config::read('Weather.options'));
		$response = json_decode(file_get_contents('./$archive/weather_2016-04-14.json'));
		if ( !empty($response) )
		{
			// current info
			$current = $response->currently;
			$this->current_temp = $current->temperature;
			$this->current_precip = $current->precipProbability;
			$this->img_current_icon = $current->icon;
			// daily info
			$day = $response->hourly;
			$this->day_summary = $day->summary;
			// calculate average over the whole day
			foreach ( $day->data as $datum )
			{
				$this->day_precip += $datum->precipProbability;
			}
			$this->day_precip = $this->day_precip / count($day->data);
			$this->img_day_icon = $day->icon;
			var_dump($this);
		}
    }

}