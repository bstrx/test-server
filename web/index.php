<?php
use MyServer\Core\Db;
use MyServer\Core\Router;
use MyServer\Core\Session;
use MyServer\Core\Request;
use MyServer\Core\Autoloader;
use MyServer\Core\ServiceContainer;

require_once('../src/MyServer/Core/Autoloader.php');
$autoloader = new Autoloader();
$autoloader->register();

$session = new Session();
$session->init();

//allow to use service globally
ServiceContainer::set('db', new Db('myserver', 'user', 'password'));
ServiceContainer::set('session', $session);

$url = isset($_GET['url']) ? $_GET['url'] : '';
$router = new Router($url);

$request = new Request();
$action = $router->getAction();
$response = $router->getControllerInstance()->$action($request);
echo json_encode($response);