<?php
/*
 * Example script that reads regions information from Real Estate Verticals Open API.
 */

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

//no need to log in in this case - this information is available without logging in

echo "Listing all regions...\n";

//get data about all regions
$regions = $api->getDictionaries()->getRegions();
echo count($regions) . " regions found:\n";
foreach ($regions as $region) {
    echo "- ID: {$region->id}, name: {$region->name['pl']}\n";
}

echo "Reading information about a single region...\n";
$region = $api->getDictionaries()->getRegion(1);
print_r($region);
