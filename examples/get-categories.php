<?php
/*
 * Example script that reads categories information from Real Estate Verticals Open API.
 */

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

//no need to log in in this case - information about categories is available without logging in

//get data about all root (!) categories
$response = $api->getDictionaries()->getCategories();
print_r($response);

//get information about a single category
$response = $api->getDictionaries()->getCategory(101);
print_r($response);
