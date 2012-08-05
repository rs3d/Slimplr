<?php

include ('functions.php');
include ('Slimpr.php');

#show ($_SERVER['SCRIPT_NAME']);
// Workaround/Bugfix for SLIM 1.6.4
$_SERVER['SCRIPT_NAME'] = '/index.php';
#show ($_SERVER['SCRIPT_NAME']);

$config = array(
	'xml-navigation-pub' => '/routes/application.xml',
	#'xml-navigation-pub' => '/routes/navigation-pub.xml',
	#'xml-navigation-pub' => '/routes/performance-test.xml', // Performance-TEst

);

new Slimpr($config);