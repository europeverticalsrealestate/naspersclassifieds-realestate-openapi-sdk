<?php
/*
 * Example script that reads categories information from Real Estate Verticals Open API.
 */

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

//no need to log in in this case - information about categories is available without logging in

//get data about all root (!) categories
$categories = $api->getDictionaries()->getCategories();
print_r($categories);

//get information about a single category
$category = $api->getDictionaries()->getCategory(101);
print_r($category);
