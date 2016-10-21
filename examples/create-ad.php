<?php
/*
 * Example script that creates a new ad via Real Estate Verticals Open API.
 */

require 'vendor/autoload.php';
require 'init.php';

echo "Logging in...\n";
$client = createClient();
$accessToken = logIn($client);
echo "Logged in.\n";

//If we want to have an agent attached to the ad, we need to pick one. In this example we choose an agent randomly.
//In a normal application, you will probably allow your user to choose an agent.
echo "Reading all agents of currently logged-in user...\n";
$response = $client->request('GET', "account/agents?access_token=$accessToken");
$body = json_decode($response->getBody()->getContents(), true);
if (empty($body['results'])) {
    echo "Warning! You do not have any agents. Please create one using manage-agents script or via the website.\n";
    $agentId = null;
} else {
    $agents = $body['results'];
    $agent = $agents[array_rand($agents)];
    $agentId = $agent['id'];
    echo "Agent {$agent['id']}, {$agent['name']} was chosen.\n";
}

echo "Uploading photos...\n";
$response = $client->request(
    'POST',
    'imageCollections',
    [
        'query' => ['access_token' => $accessToken],
        'form_params' => [
            //you can provide a normal (eg. http) URL or use a "data" protocol
            "1" => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(PATH_TO_PHOTOS . '/0001.jpg')),
            "2" => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(PATH_TO_PHOTOS . '/0002.jpg'))
        ]
    ]
);
$body = json_decode($response->getBody()->getContents(), true);
$imageCollectionId = $body['id'];
echo "Created a new image collection with ID: {$imageCollectionId}\n";

echo "Uploading one more photo...\n";
$response = $client->request(
    'PUT',
    "imageCollections/$imageCollectionId/images",
    [
        'query' => ['access_token' => $accessToken],
        'form_params' => [
            'source' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(PATH_TO_PHOTOS . '/0003.jpg'))
        ]
    ]
);

echo "Creating an ad...\n";

$ad = [
  "title" => "Example ad added via OpenAPI",
  "description" => "Lorem <strong>ipsum dolor sit amet</strong>, consectetur adipiscing elit. Donec molestie tempor "
    . "urna at vulputate. Nullam porta odio quam, ac <em>rutrum nulla</em> commodo id. Maecenas dapibus "
    . "quis neque vel volutpat. Aliquam <u>erat volutpat</u>. Vivamus nec dui vulputate, facilisis augue "
    . "sit amet, aliquam nulla.\nSed feugiat sollicitudin varius.",
  "external_id" => "MYPROGRAM-" . mt_rand(1, 10000),
  "category_id" => 101,       //flats for sale
  "region_id" => 1,           //wielkopolskie, Poland
  "city_id" => 1,             //Poznań, Poland
  "district_id" => 80,        //Jeżyce - a district in Poznań, Poland
  "coordinates" => [
    "latitude" => 44.79343,
    "longitude" => 23.16014,
    "radius" => 0,
    "zoom_level" => 12
  ],
  "advertiser_type" => "business",
  "params" => [
    "price" => [
      "0" => "price",     //always "price"
      "1" => 100000,
      "currency" => "PLN"
    ],
    "m" => 87,
    "rooms_num" => 3,
    "market" => "secondary"
  ],
  "image_collection_id" => $imageCollectionId,
  "agent" => ["id" => $agentId]
];

$response = $client->request(
    'POST',
    'account/adverts',
    [
        'query' => ['access_token' => $accessToken, "XDEBUG_SESSION_START" => "PHPSTORM"],
        'form_params' => $ad
    ]
);

$body = json_decode($response->getBody()->getContents(), true);
$adId = $body['id'];
echo "Created a new ad with ID: $adId (status: {$body['status']}) \n";

echo "Activating the ad...\n";
$response = $client->request('POST', "account/adverts/{$adId}/activate?access_token=$accessToken");
echo "Activated.\n";

echo "Reading the ad details again...\n";
$response = $client->request('GET', "account/adverts/{$adId}?access_token=$accessToken");
$body = json_decode($response->getBody()->getContents(), true);
echo "Status: '{$body['status']}', valid to: {$body['valid_to']}, available on: {$body['url']} .\n";

