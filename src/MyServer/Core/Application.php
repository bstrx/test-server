<?php
namespace MyServer\Core;

use \Memcached;
use \Exception;
use MyServer\Core\Authentication\Authenticator;

/**
 * Main workflow of application
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class Application
{
    /**
     * Configures and runs the whole app from request to response
     */
    public function run()
    {
        try {
            $this->initializeServices();
            $request = new Request();

            $authenticator = new Authenticator($request->get('auth'));
            $user = $authenticator->authenticateUser();
            if (!$user) {
                throw new Exception('Invalid user');
            }

            $router = new Router(isset($_GET['url']) ? $_GET['url'] : '');
            $action = $router->getAction();
            $responseData = $router->getControllerInstance()->$action($request);

            $response = new Response($responseData);
        } catch (Exception $e){
            $response = new Response($e->getMessage(), false);
        }

        echo $response->getJson();
    }

    /**
     * Creates core services for global usage
     */
    private function initializeServices()
    {
        $settings = $this->getSettings();

        $memcached = new Memcached();
        $memcached->addServer($settings['memcached']['host'], $settings['memcached']['port']);

        $session = new Session();

        $database = new Db(
            $settings['database']['db'],
            $settings['database']['user'],
            $settings['database']['password']
        );

        //allow to use service globally
        ServiceContainer::set('db', $database);
        ServiceContainer::set('memcached', $memcached);
        ServiceContainer::set('session', $session);
    }

    /**
     * Returns settings
     * Should be moved to .ini or similar
     * @return array
     */
    private function getSettings()
    {
        return [
            'memcached' => [
                'host' => 'localhost',
                'port' => 11211
            ],
            'database' => [
                'db' => 'myserver',
                'user' => 'user',
                'password' => 'password'
            ]
        ];
    }
}