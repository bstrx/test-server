<?php

$data = [
    'auth' => [
        'personId' => '03d59e663c1af9ac33a9949d1193505a',
        'networkKey' => 'vk',
        'authKey' => '3097e26b7f3cbdb920765a6c3d2ba94985e465c'
    ],

    'data' => [
        'info' => ['level', 'money'],
        'properties' => ['someProperty', 'anotherProperty']
    ]
];

executeAndPrint('http://test-server.dev/user/get-info', $data);

$data = [
    'auth' => [
        'sessionId' => 'some wrong session id',
    ],

    'data' => [
        'info' => [
            'level' => 55
        ],
        'properties' => [
            'someProp' => 'Not very long text',
            'customProp2' => 3217854
        ]
    ]
];
//TODO

function processCurlJsonRequest($url, $data)
{
    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, "XDEBUG_SESSION=PHPSTORM;");
    $result = curl_exec($ch);

    return json_decode($result, true);
}

function executeAndPrint($url, $data)
{
    echo '----------------------------Request----------------------------';
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    echo '<br>';

    echo '----------------------------Response----------------------------';
    echo '<pre>';
    print_r(processCurlJsonRequest($url, $data));
    echo '</pre>';
}