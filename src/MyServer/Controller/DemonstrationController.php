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
        $data = [
            'auth' => [
                'sessionId' => 'some wrong session id',
                'personId' => 'FdfsdafsaesfafFE',
                'networkKey' => 'vk',
                'authKey' => 'fasfkjldaskl;fjeawifjwaiofjwoijfkflsjaklfas'
            ],

            'user' => [
                'info' => ['level'],
                'properties' => ['someProp']
            ]
        ];

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
