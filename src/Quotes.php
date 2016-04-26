<?php
/**
 * Quotes class
 * Used for providing Quotes data.
 */
namespace LockerHelper;

class Quotes implements \JsonSerializable
{
    const NUM_QUOTES = 4;

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
            $xml = simplexml_load_file(Config::read('quotes.URL'));

            for ($i = 0; $i < self::NUM_QUOTES; $i++) {
                $title = (string) $xml->channel->item[$i]->title;
                $link = (string) $xml->channel->item[$i]->link;
                $description = (string) $xml->channel->item[$i]->description;

                $q = new Quote($title, $description);
                array_push($this->list, $q);
            }            
        } catch (\Exception $e) {
            exit($e->getMessage() . "\n");
        }
    }

}

class Quote implements \JsonSerializable
{
	private $title;
	private $description;

    public function __construct($tl, $ds)
    {
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