<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use naspersclassifieds\realestate\openapi\model\Agent;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;

class OpenApiClientAccountTest extends OpenApiTestCase
{
    /**
     * @var Agent
     */
    private $exampleAgent;

    public function setUp()
    {
        parent::setUp();
        $exampleAgent = new Agent();
        $exampleAgent->id = 2199610;
        $exampleAgent->phone = "555 666 777";
        $exampleAgent->name = "Imi Nazwonko";
        $exampleAgent->email = "kulamula@hula.ho";
        $exampleAgent->photo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAnUlEQVR42mL' .
            '4//8/AwgDgT0QbwTiJ1AMYtvD5aGKKoD4Pw5cAVUDNukPEG8HYksgZoZiS6gYSM6GEUgcBuKPQF0+DFgAIyPjaiAlCmJ/AGJdIN4' .
            'Lwkhu3gvFILkPLFCNN4H4F5phv5DkQE4BW60LMwkdQ008APPMOiSJh0B8CYm/AuQZ5OBZB/IpkgJLqFgFPBzRAvw5FKMEOECAAQA' .
            '6DXcHpH9ICAAAAABJRU5ErkJggg==';
        $this->exampleAgent = $exampleAgent;
    }
    
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
        $this->assertEquals($this->exampleAgent->name, $agents[0]->name);
        $this->assertEquals($this->exampleAgent->phone, $agents[0]->phone);
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
        $agent = $this->openApi->getAccount()->getAgent($this->exampleAgent->id);

        $this->assertAuthorizedRequest('account/agents/' . $this->exampleAgent->id);
        $this->assertEquals($this->exampleAgent->name, $agent->name);
        $this->assertEquals($this->exampleAgent->phone, $agent->phone);
        $this->assertEquals($this->exampleAgent->id, $agent->id);
    }

    public function testShouldNotRetrieveAgentIfIdNotExists()
    {
        $this->logInIntoApi();

        $this->addResponse(404);

        $agentId = $this->exampleAgent->id + 1;
        try {
            $this->openApi->getAccount()->getAgent($agentId);
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertAuthorizedRequest('account/agents/' . $agentId);
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testShouldChangeAgentDetails()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.agents.2.response.json');
        $this->exampleAgent->name = "Kolo Rollo";

        $agentResponse = $this->openApi->getAccount()->setAgent($this->exampleAgent);

        $expectedBody = json_encode($this->exampleAgent);
        $this->assertAuthorizedRequest('account/agents/' . $this->exampleAgent->id, 'PUT', [], [], $expectedBody);

        $this->assertEquals($this->exampleAgent->name, $agentResponse->name);
        $this->assertEquals($this->exampleAgent->phone, $agentResponse->phone);
        $this->assertEquals($this->exampleAgent->id, $agentResponse->id);
    }

    public function testShouldDeleteAgentPhoto()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.agents.3.response.json');
        $this->exampleAgent->photo = false;

        $agentResponse = $this->openApi->getAccount()->setAgent($this->exampleAgent);

        $expectedBody = json_encode($this->exampleAgent);
        $this->assertAuthorizedRequest('account/agents/' . $this->exampleAgent->id, 'PUT', [], [], $expectedBody);

        $this->assertNull($agentResponse->photo);
        $this->assertEquals($this->exampleAgent->id, $agentResponse->id);
    }

    public function testShouldAddNewAgent()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.agents.1.response.json');
        unset($this->exampleAgent->id);
        $agentResponse = $this->openApi->getAccount()->addAgent($this->exampleAgent);
        $expectedBody = json_encode($this->exampleAgent);
        $this->assertAuthorizedRequest('account/agents', 'POST', [], [], $expectedBody);

        $this->assertEquals($this->exampleAgent->name, $agentResponse->name);
        $this->assertGreaterThan(0, $agentResponse->id);
    }
}
