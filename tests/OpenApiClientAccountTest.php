<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;

class OpenApiClientAccountAgentsTest extends OpenApiTestCase
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
}
