<?php

$data = [
    'auth' => [
        'sessionId' => 'some wrong session id',
        'personId' => 'test_id',
        'networkKey' => 'vk',
        'authKey' => 'test_auth'
    ],

    'user' => [
        'info' => ['level'],
        'properties' => ['someProp']
    ]
];

var_dump(processCurlJsonRequest('http://test-server.dev/user/get-info', $data));

function processCurlJsonRequest($url, $data)
{
    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    return $result;
}