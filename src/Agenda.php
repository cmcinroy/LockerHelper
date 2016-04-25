<?php
/**
 * Agenda class
 * Used for providing Timetable/schedule data.
 */
namespace LockerHelper;

class Agenda implements \JsonSerializable
{
    private $list;

    public function __construct()
    {
		// update vars
        $this->update();
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


    // refresh values 
    public function update()
    {
        try {
            $this->list = json_decode(file_get_contents('./_archive/schedule.json'));
        } catch (\Exception $e) {
            exit($e->getMessage() . "\n");
        }
    }

}

class AgendaItem
{
	private $time;
	private $subject;
}