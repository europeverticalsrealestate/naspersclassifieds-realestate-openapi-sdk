<?php
/*
 * Example script that creates, modifies and reads agents via Real Estate Verticals Open API.
 * An agent (also called broker or realtor) is a person who acts as an intermediary between sellers and buyers
 * of real estate/real property and attempts to find sellers who wish to sell and buyers who wish to buy.
 * In storia/otodom websites an agent can be attached to an ad. Once it is done, agent's contact data is displayed
 * on the ad page.
 */

require 'vendor/autoload.php';
require 'init.php';

echo "Logging in...\n";
$client = createClient();
$accessToken = logIn($client);
echo "Logged in.\n";

echo "Creating a new agent with random data...\n";

$fakeName = "John " . generateRandomName();
$fakeEmail = str_replace(' ', '.', strtolower($fakeName)) . '@example.domain';
$agent = [
    'name' => $fakeName,
    'email' => $fakeEmail,
    'phone' => mt_rand(100000000, 999999999),
    //you can provide a normal (eg. http) URL or use a "data" protocol
    'photo' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(PATH_TO_PHOTOS . '/smiley.jpg')),
];

$response = $client->request(
    'POST',
    'account/agents',
    [
        'query' => ['access_token' => $accessToken],
        'form_params' => $agent
    ]
);

$agent = json_decode($response->getBody()->getContents(), true);
echo "Created a new agent with name: '{$agent['name']}' and ID: {$agent['id']}.\n";


echo "Reading all agents of currently logged-in user...\n";
$response = $client->request('GET', "account/agents?access_token=$accessToken");
$body = json_decode($response->getBody()->getContents(), true);
displayAgentsList($body['results']);


echo "Changing name of the agent {$agent['id']}...\n";
$newName = "Bob " . generateRandomName();
$agent['name'] = $newName;
$response = $client->request(
    'PUT',
    "account/agents/{$agent['id']}",
    [
        'query' => ['access_token' => $accessToken, 'XDEBUG_SESSION_START'=>'PHPSTORM'],
        'form_params' => ['name' => $newName]
    ]
);
$agent = json_decode($response->getBody()->getContents(), true);
echo "Server confirmed that new name of the agent {$agent['id']} is now: '{$agent['name']}'.\n";


//------------------ utility functions below  --------------------------

/* Just a quick and dirty function to generate random strings. */
function generateRandomName() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyz';
    $length = mt_rand(3, 10);
    $name = '';
    for($i=0;$i<$length;$i++) {
        $name .= $alphabet[mt_rand(0,strlen($alphabet)-1)];
    }
    return ucfirst($name);
}

function displayAgentsList($agentsList) {
    if (empty($agentsList)) {
        echo "No agents found.\n";
    } else {
        echo sprintf('%-10s | %-15s | %s', 'ID', 'Phone', 'Name') . "\n";
        echo str_repeat('-', 75) . "\n";
        foreach ($agentsList as $agent) {
            $phone = isset($agent['phone']) ? $agent['phone'] : '';
            $name = isset($agent['name']) ? $agent['name'] : '';
            echo sprintf('%10d | %-15s | %s', $agent['id'], $phone, $name) . "\n";
        }
    }
}

