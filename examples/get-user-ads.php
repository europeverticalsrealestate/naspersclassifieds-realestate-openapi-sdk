<?php
/*
 * Example script that reads ads that belong to currently logged-in user via Real Estate Verticals Open API.
 */

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

echo "Logging in...\n";
$api->logIn(OPENAPI_KEY, OPENAPI_SECRET, OTODOM_USER, OTODOM_PASSWORD);
echo "Logged in.\n";

echo "Reading first 20 ads ordered by creation time...\n";

$query = (new naspersclassifieds\realestate\openapi\query\AccountAdvertsQuery)
    ->setLimit(20)
    ->setSortBy(naspersclassifieds\realestate\openapi\query\AccountAdvertsQuery::SORT_BY_CREATED_AT)
    ->setSortDirection(naspersclassifieds\realestate\openapi\query\AccountAdvertsQuery::SORT_ASC)
;

$results = $api->getAccount()->getAdverts($query);

if (empty($results->results)) {
    echo "No ads. Please add some ads using the create-ad script or via the webpage.\n";
    exit;
}
echo "Showing page {$results->current_page} of {$results->total_pages}" .
    " containing {$results->current_elements} out of {$results->total_elements} ads:\n";

echo sprintf('%-10s | %-15s | %s', 'ID', 'Status', 'Title') . "\n";
echo str_repeat('-', 75) . "\n";
foreach ($results->results as $ad) {
    echo sprintf('%10d | %-15s | %s', $ad->id, $ad->status, $ad->title) . "\n";
}
