<?php

error_reporting(E_ALL);
include '/var/www/Autoloader/AutoLoader.php';

use AutoLoader\AutoLoader;
use ServiceLocator\ServiceLocator;

$autoloader = new AutoLoader('./', array('ServiceLocator'=>'/var/www/ServiceLocator'));
