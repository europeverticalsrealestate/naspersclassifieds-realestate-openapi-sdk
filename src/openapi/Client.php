<?php
namespace naspersclassifieds\realestate\openapi;


use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use stdClass;

class Client
{

    private $options = ['headers' => ['Accept' => 'application/json']];

    /**
     * @var Uri
     */
    private $baseUri;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * OpenApiClient constructor.
     * @param string $baseApiUrl base url of open api
     * @param ClientInterface $httpClient optional http client (used for tests)
     */
    public function __construct($baseApiUrl, ClientInterface $httpClient = null)
    {
        $this->baseUri = new Uri($baseApiUrl);
        $this->httpClient = is_null($httpClient) ? new GuzzleHttpClient() : $httpClient;
    }

    /**
     * @param $resource
     * @return stdClass
     * @throws RequestException
     */
    public function getFrom($resource)
    {
        $response = $this->httpClient->get(Uri::resolve($this->baseUri, $resource), $this->options);
        return json_decode($response->getBody()->getContents());
    }
}