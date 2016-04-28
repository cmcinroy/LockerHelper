<?php
/**
 * Announcements class
 * Used for providing announcement data.
 */
namespace LockerHelper;

class Announcements implements \JsonSerializable
{
    private $list;

    public function __construct()
    {
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
            $response = json_decode(file_get_contents('./cache/announcements.json'));
            foreach ($response as $announcement) {
            $a = new Announcement($announcement->title, $announcement->text);
            array_push($this->list, $a);
            }
        } catch (\Exception $e) {
            exit($e->getMessage() . "\n");
        }
    }

}

class Announcement implements \JsonSerializable
{
	private $title;
	private $text;

    public function __construct($tl, $tx)
    {
        $this->title = $tl;
        $this->text = $tx;
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
