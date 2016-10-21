<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\OpenApi;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;
use naspersclassifieds\realestate\openapi\tests\utils\HttpClientMocker;
use PHPUnit_Framework_TestCase;

abstract class OpenApiTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @var HttpClientMocker
     */
    protected $mocker;

    /**
     * @var OpenApi
     */
    protected $openApiClient;

    protected function setUp()
    {
        $this->mocker = new HttpClientMocker();
        $this->openApiClient = new OpenApi(Constants::API_URL, $this->mocker->getClient());
    }

    /**
     * @param string $target
     * @param string $method optional, GET by default
     */
    protected function assertRequest($target, $method = 'GET')
    {
        $lastRequest = $this->mocker->getLastRequest();
        $this->assertEquals(Constants::API_URL . $target, (string)$lastRequest->getUri());
        $this->assertEquals($method, (string)$lastRequest->getMethod());
    }
}