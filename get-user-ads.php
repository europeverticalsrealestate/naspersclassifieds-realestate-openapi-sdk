<?php
/*
 * Example script that reads ads that belong to currently logged-in user via Real Estate Verticals Open API.
 */

require 'vendor/autoload.php';
require 'init.php';

echo "Logging in...\n";
$client = createClient();
$accessToken = logIn($client);
echo "Logged in.\n";

echo "Reading first 20 ads ordered by creation time...\n";
$response = $client->request(
    'GET',
    "account/adverts",
    [
        'query' => [
            'access_token' => $accessToken,
            'limit' => 20,
            'sortby' => 'created_at_first',
            'sortdirection' => 'desc'
        ]
    ]
);
$body = json_decode($response->getBody()->getContents(), true);

if (empty($body['results'])) {
    echo "No ads. Please add some ads using the create-ad script or via the webpage.\n";
    exit;
}
echo "Showing page {$body['current_page']} of {$body['total_pages']} containing {$body['current_elements']} out of {$body['total_elements']} ads:\n";

echo sprintf('%-10s | %-15s | %s', 'ID', 'Status', 'Title') . "\n";
echo str_repeat('-', 75) . "\n";
foreach ($body['results'] as $ad) {
    echo sprintf('%10d | %-15s | %s', $ad['id'], $ad['status'], $ad['title']) . "\n";
}
