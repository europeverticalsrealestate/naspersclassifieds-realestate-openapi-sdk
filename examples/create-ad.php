<?php
/*
 * Example script that creates a new ad via Real Estate Verticals Open API.
 */

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

echo "Logging in...\n";
$api->logIn(OPENAPI_KEY, OPENAPI_SECRET, OTODOM_USER, OTODOM_PASSWORD);
echo "Logged in.\n";


//If we want to have an agent attached to the ad, we need to pick one. In this example we choose an agent randomly.
//In a normal application, you will probably allow your user to choose an agent.
echo "Reading all agents of currently logged-in user...\n";
$agents = $api->getAccount()->getAgentsManager()->getAgents();
if (empty($agents)) {
    echo "Warning! You do not have any agents. Please create one using manage-agents script or via the website.\n";
    $agentId = null;
    exit;
}

$agent = $agents[array_rand($agents)];
echo "Agent {$agent->id}, {$agent->name} was chosen.\n";


echo "Uploading photos...\n";

//you can provide a normal (eg. http) URL or use a "data" protocol
$images = [
    'data:image/jpeg;base64,' . base64_encode(file_get_contents(PATH_TO_PHOTOS . '/0001.jpg')),
    'data:image/jpeg;base64,' . base64_encode(file_get_contents(PATH_TO_PHOTOS . '/0002.jpg'))
];
$imageCollection = $api->getAccount()->getAdvertsManager()->createImageCollection($images);

echo "Created a new image collection with ID: {$imageCollection->id}\n";


echo "Uploading one more photo...\n";

$image = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(PATH_TO_PHOTOS . '/0003.jpg'));
$api->getAccount()->getAdvertsManager()->addToImageCollection($imageCollection->id, $image);


echo "Creating an ad...\n";

$newAdvert = new naspersclassifieds\realestate\openapi\model\Advert();
$newAdvert->title = "Example ad added via OpenAPI";
$newAdvert->description = "Lorem <strong>ipsum dolor sit amet</strong>, consectetur adipiscing elit. Donec "
    . "urna at vulputate. Nullam porta odio quam, ac <em>rutrum nulla</em> commodo id. Maecenas dapibus "
    . "quis neque vel volutpat. Aliquam <u>erat volutpat</u>. Vivamus nec dui vulputate, facilisis augue "
    . "sit amet, aliquam nulla.\nSed feugiat sollicitudin varius.";
$newAdvert->external_id = "MYID-123";
$newAdvert->category_id = 101;       //flats for sale
$newAdvert->region_id = 1;           //wielkopolskie, Poland
$newAdvert->city_id = 1;             //Poznań, Poland
$newAdvert->district_id = 80;        //Jeżyce - a district in Poznań, Poland
$newAdvert->coordinates = [
    "latitude" => 44.79343,
    "longitude" => 23.16014,
    "radius" => 0,
    "zoom_level" => 12
];
$newAdvert->params = [
    "price" => [
        "0" => "price",     //always "price"
        "1" => 100000,
        "currency" => "PLN"
    ],
    "m" => 87,
    "rooms_num" => 3,
    "market" => "secondary"
];
$newAdvert->image_collection_id = $imageCollection->id;
$newAdvert->agent = new naspersclassifieds\realestate\openapi\model\Agent();
$newAdvert->agent->id = $agent->id;


$advert = $api->getAccount()->getAdvertsManager()->createAdvert($newAdvert);

echo "Created a new ad with ID: {$advert->id} (status: {$advert->status}) \n";

echo "Activating the ad...\n";
$api->getAccount()->getAdvertsManager()->activateAdvert($advert->id);
echo "Activated.\n";

echo "Reading the ad details again...\n";
$advert = $api->getAccount()->getAdvertsManager()->getAdvert($advert->id);

echo "Status: '{$advert->status}', valid to: {$advert->valid_to}, available on: {$advert->url} .\n";
