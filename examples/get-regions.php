<?php
/*
 * Example script that reads regions information from Real Estate Verticals Open API.
 */

require 'vendor/autoload.php';
require 'init.php';

$client = createClient();

//no need to log in in this case - this information is available without logging in

echo "Listing all regions...\n";

//get data about all regions
$response = $client->get('regions');
$decodedResponse = json_decode($response->getBody()->getContents(), true);
$regions = $decodedResponse['results'];
echo count($regions) . " regions found:\n";
foreach ($regions as $region) {
    echo "- ID: {$region['id']}, name: {$region['name']['pl']}\n";
}

echo "Reading information about a single region...\n";
$response = $client->get('regions/1');
print_r($response->getBody()->getContents());
