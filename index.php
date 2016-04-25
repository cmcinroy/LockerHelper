<html>
<head>
	<title>Locker Helper Plus+</title>
	<style type="text/css">
		<?php include('css/locker.css') ?>
	</style>
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<!--<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'>-->
	<meta name="google" value="notranslate" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<script src="js/jquery.min.js"></script>
	<script src="js/moment.min.js"></script>
</head>
<body>

<?php
	// locker.html: Locker Helper Plus+ layout
	// mirror.html: Max Braun Smart Mirror layout
	// magic.html: Michael Teeuw MagicMirror layout
    // NOTE: Should also change:
    //       - CSS include in <head> section (above)
	//       - time.dateFormat in PHP config
	include('locker.html');

	// TODO Do we need any server side logic here??
?>

<!--
<script src="js/version.js"></script>
-->
<script src="js/main.js?nocache=<?php echo md5(microtime()) ?>"></script>

</body>
</html>
