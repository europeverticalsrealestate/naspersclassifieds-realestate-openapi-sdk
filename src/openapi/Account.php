<?php
namespace naspersclassifieds\realestate\openapi;

class Account
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var AdvertsManager
     */
    private $advertsManager;

    /**
     * @var AgentsManger
     */
    private $agentsManager;

    /**
     * Account constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->advertsManager = new AdvertsManager($client);
        $this->agentsManager = new AgentsManger($client);
    }

    public function getProfile()
    {
        return $this->client->get('account/profile');
    }

    /**
     * @return AdvertsManager
     */
    public function getAdvertsManager()
    {
        return $this->advertsManager;
    }

    /**
     * @return AgentsManger
     */
    public function getAgentsManager()
    {
        return $this->agentsManager;
    }
}
