<?php
/**
 * Tweets class
 * Used for providing Twitter feed.
 * Portions obtained from:
 *   http://christianvarga.com/how-to-get-public-feeds-using-twitters-v1-1-api/
 */
namespace LockerHelper;

class Tweets implements \JsonSerializable
{
    private $username;
    private $count;
    private $feed;
    private $bearer;
    private $context;
    private $list;

    public function __construct()
    {
        $this->username = Config::read('tweets.username');
        $this->count = Config::read('tweets.count');
        $this->feed = Config::read('tweets.feed');
        $this->feed = str_replace('{$username}', $this->username, $this->feed);
        $this->feed = str_replace('{$count}', $this->count, $this->feed);
        $this->bearer = Config::read('tweets.bearer');
        $this->context = stream_context_create(array(
            'http' => array(
            'method'=>'GET',
            'header'=>"Authorization: Bearer " . $this->bearer
            )
        ));
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
            //TODO could add file caching if interval smaller than threshhold
            //TODO could add time per https://wordpress.org/support/topic/can-date-and-time-be-displayed-as-time-since-tweet
            $response = json_decode(file_get_contents($this->feed, false, $this->context));
            // $response = json_decode(file_get_contents(dirname(__FILE__).'/../cache/'.'twitter-cache'));
            if ( !empty($response)) {
                foreach ($response as $tweet) {
                    //TODO could add sanitizing of text
                    // per https://blog.jacobemerick.com/web-development/parsing-twitter-feeds-with-php/
                    $t = new Tweet($tweet->created_at, $tweet->text);
                    array_push($this->list, $t);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            return false;
        }
    }
}

class Tweet implements \JsonSerializable
{
    private $time;
    private $text;

    public function __construct($tm, $tx)
    {
        $this->time = $tm;
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