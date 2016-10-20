<?php
require 'config.php';

function createClient() {
    $client = new GuzzleHttp\Client(
        [
            'base_uri' => OPENAPI_URL,
            'headers' => ['Accept' => 'application/json']
        ]
    );
    return $client;
}

function logIn($client) {
    $response = $client->request(
        'POST',
        'oauth/token',
        [
            'auth' => [OPENAPI_KEY, OPENAPI_SECRET],
            'form_params' => [
                'grant_type' => 'password',
                'username' => OTODOM_USER,
                'password' => OTODOM_PASSWORD
            ]
        ]
    );
    
    $body = json_decode($response->getBody()->getContents(), true);
    $accessToken = $body['access_token'];
    return $accessToken;
}


//TODO: include some descriptions (documentation) somewhere, eg. what is the structure of returned information
//(for example what exactly does "GET categories" return)
