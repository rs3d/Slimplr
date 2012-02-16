<?php

define('REQUEST_URI',$_SERVER['REQUEST_URI']);
define('BASE_PATH', dirname(__FILE__));
define('BASE_RELPATH', dirname($_SERVER['PHP_SELF']));

#require_once(BASE_PATH.'/app/BuilderFront.php');
require_once(BASE_PATH.'/app/BuilderDev.php');