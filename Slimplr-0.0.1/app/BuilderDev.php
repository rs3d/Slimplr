<?php

include ('functions.php');
include ('Slimpr.php');
$config = array(
	#'xml-navigation-pub' => '/routes/navigation-pub.xml',
	'xml-navigation-pub' => '/routes/performance-test.xml', // Performance-TEst

);

new Slimpr($config);