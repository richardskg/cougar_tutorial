<?php

namespace CougarTutorial;

use Cougar\Autoload\FlexAutoload;

// Initialize the Cougar framework
require_once("cougar.php");

// Add the application path to the FlexAutoload autoloader
FlexAutoload::addPath(__DIR__);
?>
