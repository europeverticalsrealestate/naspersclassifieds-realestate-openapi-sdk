<?php
namespace naspersclassifieds\realestate\openapi;


class Account
{

    /**
     * @var Client
     */
    private $client;

    /**
     * Account constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getProfile()
    {
        return $this->client->getFrom('account/profile');
    }
}