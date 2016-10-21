<?php
namespace naspersclassifieds\realestate\openapi;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use stdClass;

class OpenApiClient
{
    private $options = ['headers' => ['Accept' => 'application/json']];

    /**
     * @var Uri
     */
    private $baseUri;

    /**
     * OpenApiClient constructor.
     * @param string $baseApiUrl base url of open api
     * @param Client $httpClient optional http client (used for tests)
     */
    public function __construct($baseApiUrl, Client $httpClient = null)
    {
        $this->baseUri = new Uri($baseApiUrl);
        $this->httpClient = is_null($httpClient) ? new Client() : $httpClient;
    }

    /**
     * @param $resource
     * @return stdClass
     */
    private function getFrom($resource)
    {
        $response = $this->httpClient->get(Uri::resolve($this->baseUri, $resource), $this->options);
        return json_decode($response->getBody()->getContents());
    }

    public function getCities()
    {
        return $this->getFrom('cities')->results;
    }

    /**
     * @param integer $id city id
     * @return stdClass
     */
    public function getCity($id)
    {
        return $this->getFrom('cities/' . (int)$id);
    }

    public function getCategories()
    {
        return $this->getFrom('categories')->results;
    }

    /**
     * @param integer $id category id
     * @return stdClass
     */
    public function getCategory($id)
    {
        return $this->getFrom('categories/' . (int)$id);
    }
}