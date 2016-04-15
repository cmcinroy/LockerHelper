<html>
<head>
	<title>Locker Helper Plus+</title>
	<style type="text/css">
		<?php include('css/mirror.css') ?>
	</style>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'>
	<meta name="google" value="notranslate" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>
<body>

<?php
	require __DIR__ . '/vendor/autoload.php';

	use LockerHelper\Config;
	use LockerHelper\Weather;

	// Load configuration file
	try {
	    Config::load('default.conf.php');
	} catch (\Exception $e) {
	    exit($e->getMessage() . "\n");
	}

	// Instantiate Weather object
	$weather = new Weather();
	echo $weather->render();

	//include(dirname(__FILE__).'/weather.php');
	//include(dirname(__FILE__).'/quote.php');
	//include(dirname(__FILE__).'/schedule.php');
	//include(dirname(__FILE__).'/announcements.php');
	//include(dirname(__FILE__).'/assignments.php');
	//include(dirname(__FILE__).'/twitter.php');
?>
	<!-- michael teeuw MagicMirror layout -->
	<!--
	<div class="top right"><div class="windsun small dimmed"></div><div class="temp"></div><div class="forecast small dimmed"></div></div>
	<div class="top left"><div class="date small dimmed"></div><div class="time" id="time"></div><div class="calendar xxsmall"></div></div>
	<div class="center-ver center-hor"><!__ <div class="dishwasher light">Vaatwasser is klaar!</div> __></div>
	<div class="lower-third center-hor"><div class="compliment light"></div></div>
	<div class="bottom center-hor"><div class="news medium"></div></div>
	-->

	<!-- max braun Mirror layout -->
	<div class="top left"><div class="temp">59&deg;</div><br/><div class="forecast">Light rain tonight and tomorrow morning</div><br/><div class="pop">30&#37;</div></div>
	<div class="top right"><div class="time">3:26</div><div class="date">Thursday<br/>January 28</div></div>

	<!-- Locker Helper layout -->
	<div class="top center-hor"><div class="time"></div><div class="date"></div><div class="temp"></div><div class="forecast"></div></div>
	<div class="top left"><div class="quote"></div><div class="schedule"></div></div>
	<div class="bottom left"><div class="announce"></div></div>
	<div class="bottom center-hor"><div class="assign"></div></div>
	<div class="bottom right"><div class="twitter"></div></div>

</body>
</html>
