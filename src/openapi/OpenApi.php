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
     * @var Account
     */
    private $account;

    /**
     * @var Search
     */
    private $search;

    /**
     * OpenApiClient constructor.
     * @param string $baseApiUrl base url of open api
     * @param ClientInterface $httpClient optional http client (used for tests)
     */
    public function __construct($baseApiUrl, ClientInterface $httpClient = null)
    {
        $this->client = new Client($baseApiUrl, $httpClient);
        $this->dictionaries = new Dictionaries($this->client);
        $this->account = new Account($this->client);
        $this->search = new Search($this->client);
    }

    public function getDictionaries()
    {
        return $this->dictionaries;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    public function getSearch()
    {
        return $this->search;
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
        $this->client->logOut();
    }
}