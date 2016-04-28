<?php
/**
 * Events class
 * Used for providing calendar event data.
 * Some code/info borrowed from:
 * - http://spunmonkey.design/display-contents-google-calendar-php/
 */
namespace LockerHelper;

class Events implements \JsonSerializable
{
    const APP_NAME = 'Student Portal';
    const MAX_ITEMS = 8;

    private $cal;
    private $list;

    public function __construct()
    {
        $client = new \Google_Client();
        $client->setApplicationName(self::APP_NAME);
        $client->setDeveloperKey(Config::read('events.key'));

        $this->cal = new \Google_Service_Calendar($client);

        $this->list = array();

		// update vars
        $this->update();
    }

    // render the object as a JSON string
    public function jsonSerialize()
    {
        return $this->list;
    }


    // refresh values 
    public function update()
    {
        try {
            // the calendar id, found in calendar settings. if your calendar is through google apps
            // you may need to change the central sharing settings. the calendar for this script
            // must have all events viewable in sharing settings.
            $calendarId = Config::read('events.id');
            // tell google how we want the events
            $params = array(
            // can't use time min without singleevents turned on,
            // it says to treat recurring events as single events
                'singleEvents' => true,
                'orderBy' => 'startTime',
                'timeMin' => date(\DateTime::ATOM), // only pull events starting today
            'maxResults' => self::MAX_ITEMS // only use this if you want to limit the number
                              // of events displayed
             
            );

            // this is where we actually put the results into a var
            $events = $this->cal->events->listEvents($calendarId, $params); 
            $calTimeZone = $events->timeZone; // get the tz of the calendar

            // set the default timezone so php doesn't complain. set to your local time zone.
            date_default_timezone_set($calTimeZone);

            foreach ($events->getItems() as $event) {
                // Convert date to month and day
                $eventDateStr = $event->start->dateTime;
                if(empty($eventDateStr)) {
                    // it's an all day event
                    $eventDateStr = $event->start->date;
                }

                $temp_timezone = $event->start->timeZone;
                // This overrides the calendar timezone if the event has a special tz
                if (!empty($temp_timezone)) {
                    $timezone = new \DateTimeZone($temp_timezone); // get the time zone
                // Set your default timezone in case your events don't have one
                } else {
                    $timezone = new \DateTimeZone($calTimeZone);
                }

                $eventdate = new \DateTime($eventDateStr, $timezone);
                $e = new Event($eventdate->format("M j"), $event->summary);
                array_push($this->list, $e);
            }
        } catch (\Exception $e) {
            exit($e->getMessage() . "\n");
        }
    }

}

class Event implements \JsonSerializable
{
	private $date;
    private $title;
	private $description;

    public function __construct($dt, $tl, $ds = null)
    {
        $this->date = $dt;
        $this->title = $tl;
        $this->description = $ds;
    }

    // render the object as a JSON string
    public function jsonSerialize()
    {
        $json = array();
        foreach($this as $key => $value)
        {
            if ( !is_object($value) )
            {
                $json[$key] = $value;
            }
        }
        return $json;
    }
}