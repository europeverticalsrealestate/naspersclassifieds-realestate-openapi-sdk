<?php
/*
 * Example script that reads cities information from Real Estate Verticals Open API.
 */

require 'vendor/autoload.php';
require 'init.php';

$client = createClient();

//no need to log in in this case - this information is available without logging in

echo "Listing all cities...\n";

//get data about all cities
$response = $client->get('cities');
$decodedResponse = json_decode($response->getBody()->getContents(), true);
$cities = $decodedResponse['results'];
echo count($cities) . " cities found. Showing 5 random cities:\n";
$randomCities = array_rand($cities, 5);
foreach ($randomCities as $key) {
    $city = $cities[$key];
    echo "- ID: {$city['id']}, name: {$city['name']}\n";
}

//get information about a single city
echo "Reading information about a single city...\n";
$response = $client->get('cities/1');
print_r($response->getBody()->getContents());
