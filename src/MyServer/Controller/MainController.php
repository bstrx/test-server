<?php
namespace MyServer\Controller;

use MyServer\Core\Request;
use MyServer\Core\Controller;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
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

        var_dump($result);
    }
}
