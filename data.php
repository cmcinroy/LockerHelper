<?php
/**
 * data page
 * Used to return data to user interface page.
 */

// Use Composer to automatically load dependencies
require __DIR__ . '/vendor/autoload.php';

use LockerHelper\Config;

if ( is_ajax() ) {

	// Load configuration file
	try {
	    Config::load('local.conf.php');
	} catch (\Exception $e) {
	    exit($e->getMessage() . "\n");
	}

	$retVar = array();
	//TODO determine whether you want the interval array indecies to be flattened
	// var_dump(Config::readAll('interval'));

	// Return UI configuration:
	// - check interval
	// - date/time formats
	// - intervals for all modules
	// - icons/symbols for all modules
	// - commit hash for local git repository
	$gitHash = trim(shell_exec('git rev-parse ' . Config::read('version.refspec')));
	$retVar['config'] = array('checkInterval' => Config::read('checkInterval')) + 
		Config::read('time') + 
		Config::readAll('interval') + 
		Config::readAll('symbol') +
		array('gitHash' => $gitHash);

	// Access post data
	$method = $_POST;
	if(isset($method['js_array'])) {
		// Sanitize the json received from the client-side
		$json_array = sanitize($method['js_array']);
		// Create an array of requested components
		$components = json_decode($json_array);
		foreach ($components as $val) {
			try {
				// Determine Class name of object that
				// corresponds to requested component
				// (uppercase first letter, i.e. quote -> Quote)
				$objType = '\\LockerHelper\\' . ucfirst($val);
				if ( class_exists($objType) ) {
					// Attempt to create (instantiate) an object of that type
					$obj = new $objType;
					// Put the object in the array to be returned
					$retVar[$val] = $obj;
				}
			} catch (\Exception $e) {
			    exit($e->getMessage() . "\n");
			}
		}
	}

	// Return the JSON string
	echo json_encode($retVar);

}
else {
	echo "Nothing to see here.";
}

//Function to check if the request for this page is an AJAX request
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/*
 * Function to clean strings
 *
 * Borrowed from ajax/php tutorial here:
 * https://www.html5andbeyond.com/jquery-ajax-json-php/
 */
function sanitize($str, $quotes = ENT_NOQUOTES){
   $str = htmlspecialchars($str, $quotes);
   return $str;
}
