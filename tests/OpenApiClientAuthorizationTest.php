<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;

class OpenApiClientAuthorizationTest extends OpenApiTestCase
{
    public function testShouldLoginSuccessfulWithValidCredentials()
    {

        $this->addResponse(200, 'token.response.json');

        $this->openApi->logIn(
            Constants::AUTH_KEY,
            Constants::AUTH_SECRET,
            Constants::USER_LOGIN,
            Constants::USER_PASSWORD
        );

        $expectedHeadersInRequest = ['Authorization' => Constants::AUTH_HEADER];
        $expectedParamsInRequest = [
            'grant_type' => 'password',
            'username' => Constants::USER_LOGIN,
            'password' => Constants::USER_PASSWORD,
        ];
        $this->assertRequest('oauth/token', 'POST', $expectedHeadersInRequest, $expectedParamsInRequest);
        $this->assertTrue($this->openApi->isLoggedIn());
    }


    public function testShouldNotLoginWithWrongClientCredentials()
    {

        $this->addResponse(403, 'token.invalid.client.response.json');

        try {
            $this->openApi->logIn(
                Constants::AUTH_KEY,
                'wrongsecret',
                Constants::USER_LOGIN,
                Constants::USER_PASSWORD
            );
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals('Cannot identify and/or authenticate the client.', $e->getMessage());
        }

        $this->assertRequest('oauth/token', 'POST');
        $this->assertFalse($this->openApi->isLoggedIn());
    }

    public function testShouldNotLoginWithWrongUserCredentials()
    {

        $this->addResponse(400, 'token.invalid.grant.response.json');

        try {
            $this->openApi->logIn(
                Constants::AUTH_KEY,
                Constants::AUTH_SECRET,
                Constants::USER_LOGIN,
                'wrongpassword'
            );
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals('Invalid login or password', $e->getMessage());
        }

        $this->assertRequest('oauth/token', 'POST');
        $this->assertFalse($this->openApi->isLoggedIn());
    }

    public function testShouldLogOut()
    {

        $this->addResponse(200, 'token.response.json');

        $this->openApi->logIn(
            Constants::AUTH_KEY,
            Constants::AUTH_SECRET,
            Constants::USER_LOGIN,
            Constants::USER_PASSWORD
        );

        $this->assertTrue($this->openApi->isLoggedIn());

        $this->openApi->logOut();
        $this->assertFalse($this->openApi->isLoggedIn());
    }
}
