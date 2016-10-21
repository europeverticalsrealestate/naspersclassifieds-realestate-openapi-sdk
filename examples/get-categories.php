<?php
/*
 * Example script that reads categories information from Real Estate Verticals Open API.
 */

require 'init.php';

$client = createClient();

//no need to log in in this case - information about categories is available without logging in

//get data about all root (!) categories
$response = $client->get('categories');
print_r($response->getBody()->getContents());

//get information about a single category
$response = $client->get('categories/101');
print_r($response->getBody()->getContents());
