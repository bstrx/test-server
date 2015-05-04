<?php
namespace MyServer\Controller;

use MyServer\Core\Request;
use MyServer\Core\Controller;

/**
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class DemonstrationController extends Controller
{
    public function indexAction(Request $request)
    {
        //TODO remove users's data
        $data = [
            'id' => 1,
            'user' => [
                'info' => ['level'],
                'properties' => ['someProp']
            ]
        ];

        $request = new Request();
        $request->set($data);

        $controller = new UserController();
        $result = $controller->getInfoAction($request);

        return $result;
    }

    public function updateAction(Request $request)
    {
        $data = [
            'id' => 1,
            'user' => [
                'info' => [
                    'level' => 55
                ],
                'properties' => [
                    'someProp' => 'Not very long text',
                    'customProp2' => 3217854
                ]
            ]
        ];

        $request = new Request();
        $request->set($data);

        $controller = new UserController();
        $result = $controller->updateInfoAction($request);

        return $result;
    }
}
