<?php
/*
 * Example script that reads cities information from Real Estate Verticals Open API.
 */

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

//no need to log in in this case - this information is available without logging in

echo "Listing all cities...\n";

$cities = $api->getDictionaries()->getCities();
echo count($cities) . " cities found. Showing 5 random cities:\n";
$randomCities = array_rand($cities, 5);
foreach ($randomCities as $key) {
    $city = $cities[$key];
    echo "- ID: {$city->id}, name: {$city->name}\n";
}

//get information about a single city
echo "Reading information about a single city...\n";
$city = $api->getDictionaries()->getCity(1);
print_r($city);
