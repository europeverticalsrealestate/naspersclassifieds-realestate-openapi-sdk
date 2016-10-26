<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use naspersclassifieds\realestate\openapi\model\Agent;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;

class OpenApiClientAccountTest extends OpenApiTestCase
{
    public function testShouldRetrieveAccountProfile()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.profile.response.json');
        $profile = $this->openApi->getAccount()->getProfile();

        $this->assertAuthorizedRequest('account/profile');
        $this->assertEquals(Constants::USER_LOGIN, $profile['email']);
    }

    public function testShouldNotRetrieveAccountProfileIfNotLoggedIn()
    {
        $this->addResponse(403, 'token.invalid.token.response.json');

        try {
            $this->openApi->getAccount()->getProfile();
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals('Token is invalid and/or expired', $e->getMessage());
        }

        $this->assertRequest('account/profile');
    }

    public function testShouldRetrieveAgents()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.agents.response.json');
        $agents = $this->openApi->getAccount()->getAgents();

        $this->assertEquals(1, count($agents));
        $this->assertAuthorizedRequest('account/agents');
        $this->assertEquals(Constants::AGENT_NAME, $agents[0]->name);
        $this->assertEquals(Constants::AGENT_PHONE, $agents[0]->phone);
    }

    public function testShouldNotRetrieveAgentsDataIfNotLoggedIn()
    {
        $this->addResponse(403, 'token.invalid.token.response.json');

        try {
            $this->openApi->getAccount()->getAgents();
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals('Token is invalid and/or expired', $e->getMessage());
        }

        $this->assertRequest('account/agents');
    }

    public function testShouldRetrieveAgentById()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.agents.1.response.json');
        $agent = $this->openApi->getAccount()->getAgent(Constants::AGENT_ID);

        $this->assertAuthorizedRequest('account/agents/' . Constants::AGENT_ID);
        $this->assertEquals(Constants::AGENT_NAME, $agent->name);
        $this->assertEquals(Constants::AGENT_PHONE, $agent->phone);
        $this->assertEquals(Constants::AGENT_ID, $agent->id);
    }

    public function testShouldNotRetrieveAgentIfIdNotExists()
    {
        $this->logInIntoApi();

        //$this->addResponse(404, 'not.found.response.json');
        $this->addResponse(404);

        try {
            $agentId = Constants::AGENT_ID + 1;
            $this->openApi->getAccount()->getAgent($agentId);
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals(404, $e->getCode());
        }

        $this->assertAuthorizedRequest('account/agents/' . $agentId);
    }

    public function testShouldChangeAgentDetails()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.agents.1.response.json');

        $agent = new Agent();
        $agent->id = Constants::AGENT_ID;
        $agent->phone  = Constants::AGENT_PHONE;
        $newAgentName = "Kolo Rollo";
        $agent->name = $newAgentName;
        $agent->photo = Constants::AGENT_PHOTO;
        $agent->email = Constants::AGENT_EMAIL;

        $agentResponse = $this->openApi->getAccount()->setAgent($agent);

        $this->assertAuthorizedRequest('account/agents/' . Constants::AGENT_ID, 'PUT');

        $this->assertEquals(Constants::AGENT_NAME, $agentResponse->name);
        $this->assertEquals(Constants::AGENT_PHONE, $agentResponse->phone);
        $this->assertEquals(Constants::AGENT_ID, $agentResponse->id);

    }

    private function logInIntoApi()
    {
        $this->addResponse(200, 'token.response.json');
        $this->openApi->logIn(
            Constants::AUTH_KEY,
            Constants::AUTH_SECRET,
            Constants::USER_LOGIN,
            Constants::USER_PASSWORD
        );
    }
}

