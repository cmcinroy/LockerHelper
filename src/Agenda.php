<?php
/**
 * Agenda class
 * Used for providing Timetable/schedule data.
 * Some code/info borrowed from:
 * - http://karl.kranich.org/2015/04/16/google-sheets-api-php/
 * - http://blog.chapagain.com.np/php-google-spreadsheet-with-oauth-add-edit-delete-view-data/
 */
namespace LockerHelper;

include_once __DIR__ . '/../vendor/google/apiclient/examples/templates/base.php';

class Agenda implements \JsonSerializable
{
    const APP_NAME = 'Student Portal';
    const SHEET_API = 'https://spreadsheets.google.com/feeds/worksheets/{$fileId}/private/full';
    const CELLS_API = 'https://spreadsheets.google.com/feeds/cells/{$fileId}/{$sheetId}/private/basic';
    const NUM_ITEMS = 8;

    private $accessToken;
    private $list;

    public function __construct()
    {
        $this->list = array();

        // Establish Google client
        $client = new \Google_Client();
        $client->setAuthConfig(__DIR__ . '/..' . Config::read('agenda.authConfig'));
        $client->setApplicationName(self::APP_NAME);
        $client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
        // Authorization
        // Some people have reported needing to use the following setAuthConfig command
        // which requires the email address of your service account (you can get that from the json file)
        // $client->setAuthConfig(["type" => "service_account", "client_email" => "my-service-account@developer.gserviceaccount.com"]);
        try {
            $tokenArray = $client->fetchAccessTokenWithAssertion();
            $this->accessToken = $tokenArray["access_token"];
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            return false;
        }

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
            //TODO getSheetNameFromStudent(Config::read('agenda.student'))...
            $id = $this->getSheetIdFromName(Config::read('agenda.class'));
            $this->list = $this->getItemsFromSheet($id);
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            return false;
        }
    }

    // Get list of worksheets and find the id for the specified named sheet
    private function getSheetIdFromName($sheetName)
    {
        $retVal = null;

        // Determine id for timetable sheet
        $url = str_replace('{$fileId}', Config::read('agenda.key'), self::SHEET_API);
        $method = 'GET';
        $headers = ["Authorization" => "Bearer $this->accessToken"];
        $httpClient = new \GuzzleHttp\Client(['headers' => $headers]);
        $resp = $httpClient->request($method, $url);
        $body = $resp->getBody()->getContents();

        $xml = simplexml_load_string($body);
        if ( !empty($xml) ) {
            foreach ($xml->entry as $entry) {
              if ( $entry->title == $sheetName ) {
                foreach ($entry->link as $link) {
                    if ( $link['rel'] == 'self' ) {
                        $retVal = end(explode('/', $link['href']));
                        break;
                    }
                }
                break;
              }
            }
        }

        return $retVal;
    }

    // Get values A20:A27 from the specified sheet
    private function getItemsFromSheet($sheetId)
    {
        $retVal = array();

        // Determine cell contents
        $url = str_replace('{$fileId}', Config::read('agenda.key'), self::CELLS_API);
        $url = str_replace('{$sheetId}', $sheetId, $url);
        $method = 'GET';
        $headers = ["Authorization" => "Bearer $this->accessToken"];
        $httpClient = new \GuzzleHttp\Client(['headers' => $headers]);
        $resp = $httpClient->request($method, $url);
        $body = $resp->getBody()->getContents();

        $xml = simplexml_load_string($body);
        if ( !empty($xml) ) {
            //TODO ensure that autorecalc works for cells with NOW()-based formulas
            //TODO otherwise, implement look-ahead logic here
            $count = 0;
            foreach ($xml->entry as $entry) {
                // Cell A20 is the start of the look-ahead results...
                if ( $entry->title == 'A' . ($count + 20) ) {
                    $subject = (string) $entry->content;
                }
                if ( $entry->title == 'B' . ($count + 20) ) {
                    $time = (string) $entry->content;
                    $count++;
                    $a = new AgendaItem($time, $subject);
                    array_push($retVal, $a);
                    // stop after specified # of items
                    if ( $count == self::NUM_ITEMS ) {
                        break;
                    }
                }
            }
        }

        return $retVal;
    }
}

class AgendaItem implements \JsonSerializable
{
	private $time;
	private $subject;

    public function __construct($tm, $sb)
    {
        $this->time = $tm;
        $this->subject = $sb;
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