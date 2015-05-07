<?php
/**
 * This is the example of two requests:
 * 1) GET info and properties using social network authentication
 * 2) SET info and properties using session id identification
 */

$serverUrl = 'http://test-server.dev';

//GET info and properties
$requestData = [
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

$url = $serverUrl . '/user/get-info';
$responseData = executeRequest($url, $requestData);
printFormated($requestData, $responseData);

echo "<br><span style='color:orangered'>!!! Now let's update some user values with the session id assigned to us in previous request !!!</span><br>";

//SET info and properties
$requestData = [
    'auth' => [
        'sessionId' => $responseData['auth']['sessionId'],
    ],

    'data' => [
        'info' => [
            'level' => 55
        ],
        'properties' => [
            'someProperty' => 'Not very long text!!',
            'anotherProperty' => 3217854,
            'newProperty' => 'new value'
        ]
    ]
];

$url = $serverUrl . '/user/update-info';
$responseData = executeRequest($url, $requestData);
printFormated($requestData, $responseData);

/**
 * @param string $url
 * @param array $data
 * @return array
 */
function executeRequest($url, array $data)
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

/**
 * @param array $requestData
 * @param array $responseData
 */
function printFormated(array $requestData, array $responseData)
{
    echo '<pre>';
    echo '----------------------------Request----------------------------';
    echo '<br>';
    print_r($requestData);
    echo '<br>';
    echo '----------------------------Response----------------------------';
    echo '<br>';
    print_r($responseData);
    echo '</pre>';
}