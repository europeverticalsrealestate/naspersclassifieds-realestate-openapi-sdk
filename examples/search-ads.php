<?php
/*
 * Example script that searches for ads in the whole site.
 */

require 'init.php';

echo "Logging in...\n";
$client = createClient();
$accessToken = logIn($client);
echo "Logged in.\n";

echo "Reading 20 newest flats for sale in Poznań, with price greater than 2000 PLN...\n";
try {
$response = $client->request(
    'GET',
    "adverts",
    [
        'query' => [
            'access_token' => $accessToken,
            'XDEBUG_SESSION_START'=> 'PHPSTORM',
            'fq' => json_encode(
                    [
                        'category_id' => 101,
                        'city_id' => 1,
                        'params' => [
                            'price' => ['from', 2000]
                        ]
                    ]
                ),
            'limit' => 20,
            'sortby' => 'created_at_first',
            'sortdirection' => 'desc'
        ]
    ]
);
$body = json_decode($response->getBody()->getContents(), true);
}
catch(Exception $e) {
    var_dump($e);
}
if (empty($body['results'])) {
    echo "No ads found.\n";
    exit;
}
echo "Showing page {$body['current_page']} of {$body['total_pages']} containing {$body['current_elements']} out of {$body['total_elements']} ads:\n";

echo sprintf('%-10s | %-15s | %-12s | %s', 'ID', 'Status', 'Price', 'Title') . "\n";
echo str_repeat('-', 75) . "\n";
foreach ($body['results'] as $ad) {
    echo sprintf('%10d | %-15s | %-12s | %s', $ad['id'], $ad['status'], $ad['params']['price'][1] . ' ' . $ad['params']['price']['currency'], $ad['title']) . "\n";
}
