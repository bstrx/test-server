<?php
use MyServer\Core\Autoloader;
use MyServer\Core\Application;

require_once('../src/MyServer/Core/Autoloader.php');
$autoloader = new Autoloader();
$autoloader->register();

$application = new Application();
$application->run();
