<?php
/*
 * Example script that creates, modifies and reads agents via Real Estate Verticals Open API.
 * An agent (also called broker or realtor) is a person who acts as an intermediary between sellers and buyers
 * of real estate/real property and attempts to find sellers who wish to sell and buyers who wish to buy.
 * In storia/otodom websites an agent can be attached to an ad. Once it is done, agent's contact data is displayed
 * on the ad page.
 */

use naspersclassifieds\realestate\openapi\model\Agent;

require 'init.php';

$api = new naspersclassifieds\realestate\openapi\OpenApi(OPENAPI_URL);

echo "Logging in...\n";
$api->logIn(OPENAPI_KEY, OPENAPI_SECRET, OTODOM_USER, OTODOM_PASSWORD);
echo "Logged in.\n";

echo "Creating a new agent with random data...\n";

$fakeName = "John " . generateRandomName();
$fakeEmail = str_replace(' ', '.', strtolower($fakeName)) . '@example.domain';

$agent = new Agent();
$agent->name = $fakeName;
$agent->email = $fakeEmail;
$agent->phone = mt_rand(100000000, 999999999);
$agent->photo = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(PATH_TO_PHOTOS . '/smiley.jpg'));

$result = $api->getAccount()->addAgent($agent);


echo "Created a new agent with name: '{$result->name}' and ID: {$result->id}.\n";


echo "Reading all agents of currently logged-in user...\n";
$agents = $api->getAccount()->getAgents();

displayAgentsList($agents);

$agent = $result;
echo "Changing name of the agent {$agent->id}...\n";
$newName = "Bob " . generateRandomName();
$agent->name = $newName;
$agent->photo = null;

$result = $api->getAccount()->setAgent($agent);

echo "Server confirmed that new name of the agent {$agent->id} is now: '{$agent->name}'.\n";


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
        return;
    }

    echo sprintf('%-10s | %-15s | %s', 'ID', 'Phone', 'Name') . "\n";
    echo str_repeat('-', 75) . "\n";
    /**
     * @var Agent $agent
     */
    foreach ($agentsList as $agent) {
        $phone = isset($agent->phone) ? $agent->phone : '';
        $name = isset($agent->name) ? $agent->name : '';
        echo sprintf('%10d | %-15s | %s', $agent->id, $phone, $name) . "\n";
    }
}

