<?php
namespace naspersclassifieds\realestate\openapi\tests;


use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
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
     * @param integer $code
     * @param string|null $fixtureFile
     */
    protected function addResponse($code, $fixtureFile = null)
    {
        $body = $fixtureFile ? $this->loadFixture($fixtureFile) : null;
        $this->client->addResponse(new Response($code, [], $body));
    }

    /**
     * @param string $target
     * @param string $method optional, GET by default
     * @param array $headers
     * @param array $params
     * @param string|null $body
     */
    protected function assertRequest($target, $method = 'GET', $headers = [], $params = [], $body = null)
    {
        $lastRequest = $this->client->getLastRequest();
        $lastUri = $lastRequest->getUri();

        $targetUri = new Uri(Constants::API_URL . $target);
        $this->assertEquals($targetUri->getHost(), $lastUri->getHost());
        $this->assertEquals($targetUri->getPath(), $lastUri->getPath());
        $this->assertEquals($targetUri->getQuery(), $lastUri->getQuery());
        $this->assertEquals($method, (string)$lastRequest->getMethod());
        $this->assertEquals(Constants::ACCEPT_HEADER, current($lastRequest->getHeader('Accept')));
        foreach ($headers as $name => $value) {
            $this->assertEquals($value, current($lastRequest->getHeader($name)));
        }
        if (!empty($params)) {
            $this->assertEquals(http_build_query($params), $lastRequest->getBody()->getContents());
        }
        if (is_string($body)) {
            $this->assertEquals($body, $lastRequest->getBody()->getContents());
        }
    }

    /**
     * @param string $target
     * @param string $method optional, GET by default
     * @param array $headers
     * @param array $params
     */
    protected function assertAuthorizedRequest($target, $method = 'GET', $headers = [], $params = [], $body = null)
    {
        $target = $target . (strstr($target, '?') ? '&': '?') .'access_token=' . Constants::AUTH_TOKEN;
        $this->assertRequest($target, $method, $headers, $params, $body);
    }

    /**
     * @param string $name
     * @return string
     */
    private static function loadFixture($name)
    {
        return file_get_contents(dirname(__FILE__) . '/fixtures/' . $name);
    }
}