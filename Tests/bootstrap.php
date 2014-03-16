<?php

error_reporting(E_ALL);
include '/var/www/CoreInterfaces/IAutoLoader.php';
include '/var/www/Autoloader/AutoLoader.php';

use AutoLoader\AutoLoader;
use ServiceLocator\ServiceLocator;

$autoloader = new AutoLoader(array(
    'ServiceLocator' => '{root}/ServiceLocator',
    'CoreInterfaces' => '/var/www/CoreInterfaces'
));
$autoloader->addAliases(array('root'=>'/var/www'));
