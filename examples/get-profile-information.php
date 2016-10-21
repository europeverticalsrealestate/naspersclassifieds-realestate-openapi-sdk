<?php
/*
 * Example script that reads logged-in user information from Real Estate Verticals Open API.
 */

require 'vendor/autoload.php';
require 'init.php';

echo "Logging in...\n";
$client = createClient();
$accessToken = logIn($client);
echo "Logged in.\n";

echo "Reading profile information...\n";
$response = $client->request('GET', "account/profile?access_token=$accessToken");
$body = json_decode($response->getBody()->getContents(), true);
print_r($body);
