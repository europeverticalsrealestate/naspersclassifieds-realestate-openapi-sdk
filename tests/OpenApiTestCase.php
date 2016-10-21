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
    protected $client;

    /**
     * @var OpenApi
     */
    protected $openApi;

    protected function setUp()
    {
        $this->client = new HttpClientMocker();
        $this->openApi = new OpenApi(Constants::API_URL, $this->client->getClient());
    }

    /**
     * @param string $target
     * @param string $method optional, GET by default
     * @param array $headers
     * @param array $params
     */
    protected function assertRequest($target, $method = 'GET', $headers = [], $params = [])
    {
        $lastRequest = $this->client->getLastRequest();
        $this->assertEquals(Constants::API_URL . $target, (string)$lastRequest->getUri());
        $this->assertEquals($method, (string)$lastRequest->getMethod());
        foreach ($headers as $name => $value) {
            $this->assertEquals($value, current($lastRequest->getHeader($name)));
        }
        if (!empty($params)) {
            $this->assertEquals(http_build_query($params), $lastRequest->getBody()->getContents());
        }

    }
}