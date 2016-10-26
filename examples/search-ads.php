<?php
/*
 * Example script that searches for ads in the whole site.
 */

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

echo "Logging in...\n";
$api->logIn(OPENAPI_KEY, OPENAPI_SECRET, OTODOM_USER, OTODOM_PASSWORD);
echo "Logged in.\n";

echo "Reading 20 newest flats for sale in Poznań, with price greater than 2000 PLN...\n";
try {

    $query = (new naspersclassifieds\realestate\openapi\query\Adverts())
        ->setCategory(101)
        ->setCity(1)
        ->setFromParam('price', 2000)
        ->setLimit(20)
        ->setSortBy(naspersclassifieds\realestate\openapi\query\Adverts::SORT_BY_CREATION_DATE)
        ->setSortDirection(naspersclassifieds\realestate\openapi\query\Adverts::SORT_DESC);
    $results = $api->getSearch()->getAdverts($query);

    if (empty($results->results)) {
        echo "No ads found.\n";
        exit;
    }
    echo "Showing page {$results->current_page} of {$results->total_pages}" .
        " containing {$results->current_elements} out of {$results->total_elements} ads:\n";

    echo sprintf('%-10s | %-15s | %-12s | %s', 'ID', 'Status', 'Price', 'Title') . "\n";
    echo str_repeat('-', 75) . "\n";
    foreach ($results->results as $ad) {
        echo sprintf(
                '%-10s | %-15s | %-12s | %s',
                $ad->id, $ad->status, $ad->params['price'][1] . ' ' . $ad->params['price']['currency'], $ad->title
            ) . "\n";
    }
} catch (Exception $e) {
    var_dump($e);
}

