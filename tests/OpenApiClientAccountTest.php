<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;

class OpenApiClientAccountTest extends OpenApiTestCase
{
    public function testShouldRetrieveAccountProfile()
    {
        $this->addResponse(200, 'token.response.json');
        $this->openApi->logIn(
            Constants::AUTH_KEY,
            Constants::AUTH_SECRET,
            Constants::USER_LOGIN,
            Constants::USER_PASSWORD
        );

        $this->addResponse(200, 'account.profile.response.json');
        $profile = $this->openApi->getAccount()->getProfile();

        $this->assertRequest('account/profile?access_token=' . Constants::AUTH_TOKEN);
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

