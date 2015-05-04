<?php
namespace MyServer\Core;

use Exception;

/**
 * @author Vladimir Prudilin
 * Converts url to controller and action
 */
class Router
{
    const DEFAULT_CONTROLLER = 'main';
    const DEFAULT_ACTION = 'index';

    /**
     * @var string controller full name
     */
    private $controller;

    /**
     * @var string action name
     */
    private $action;

    /**
     * @return Controller
     */
    public function getControllerInstance()
    {
        return new $this->controller();
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $url
     * @throws Exception
     */
    public function __construct($url)
    {
        $urlParts = explode('/', $url);
        $controller = empty($urlParts[0]) ? self::DEFAULT_CONTROLLER : $urlParts[0];
        $action = empty($urlParts[1]) ? self::DEFAULT_ACTION : $urlParts[1];

        $this->controller = sprintf('MyServer\Controller\%sController', $this->formatUrl($controller, true));
        $this->action = sprintf('%sAction', $this->formatUrl($action));

        $this->validateAction();
    }

    /**
     * Checks if specified action exist in specified controller
     *
     * @throws Exception
     */
    public function validateAction()
    {
        if (class_exists($this->controller)) {
            if (!method_exists($this->controller, $this->action)) {
                throw new Exception(sprintf('Action %s does not exist', $this->action));
            }
        } else {
            throw new Exception(sprintf('Controller %s does not exist', $this->controller));
        }
    }

    /**
     * @param string $input
     * @param bool $capitalizeFirst
     * @return string
     */
    private function formatUrl($input, $capitalizeFirst = false)
    {
        $urlParts = explode('-', $input);
        $string = $capitalizeFirst ? ucfirst($urlParts[0]) : $urlParts[0];

        $urlPartsCount = count($urlParts);
        for ($i = 1; $i < $urlPartsCount; $i++) {
            $string .= ucfirst($urlParts[$i]);
        }

        return $string;
    }
}
