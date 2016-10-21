<?php
namespace naspersclassifieds\realestate\openapi;


use GuzzleHttp\ClientInterface;

class OpenApi
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Dictionaries
     */
    private $dictionaries;

    /**
     * OpenApiClient constructor.
     * @param string $baseApiUrl base url of open api
     * @param ClientInterface $httpClient optional http client (used for tests)
     */
    public function __construct($baseApiUrl, ClientInterface $httpClient = null)
    {
        $this->client = new Client($baseApiUrl, $httpClient);
        $this->dictionaries = new Dictionaries($this->client);
    }

    public function getDictionaries()
    {
        return $this->dictionaries;
    }

    /**
     * @param string $key OpenApi key
     * @param string $secret OpenApi secret
     * @param string $login user login (e-mail)
     * @param string $password user password
     */
    public function logIn($key, $secret, $login, $password)
    {
        $this->client->logIn($key, $secret, $login, $password);
    }

    public function isLoggedIn()
    {
        return $this->client->isLoggedIn();
    }

    public function logOut()
    {
        return $this->client->logOut();
    }
}