<?php
/*
 * Example script that reads logged-in user information from Real Estate Verticals Open API.
 */

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

echo "Logging in...\n";
$api->logIn(OPENAPI_KEY, OPENAPI_SECRET, OTODOM_USER, OTODOM_PASSWORD);
echo "Logged in.\n";

echo "Reading profile information...\n";
$profile = $api->getAccount()->getProfile();
print_r($profile);
