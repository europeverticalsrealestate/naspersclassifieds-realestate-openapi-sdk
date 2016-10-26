<?php
namespace naspersclassifieds\realestate\openapi;


use naspersclassifieds\realestate\openapi\model\Advert;
use naspersclassifieds\realestate\openapi\model\AdvertsResult;
use naspersclassifieds\realestate\openapi\model\Agent;
use naspersclassifieds\realestate\openapi\query\AccountAdverts;

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

    /**
     * @param AccountAdverts $query
     * @return AdvertsResult
     */
    public function getAdverts(AccountAdverts $query = null)
    {
        return $this->client->getFromAsObject('account/adverts' . $query, AdvertsResult::class);
    }

    /**
     * @param integer $id
     * @return Advert
     */
    public function getAdvert($id)
    {
        return $this->client->getFromAsObject('account/adverts/' . (int)$id, Advert::class);
    }

    /**
     * @return array
     */
    public function getAgents()
    {
        return $this->client->getFromAsObjects('account/agents', Agent::class);
    }

    /**
     * @param integer $id
     * @return Agent
     */
    public function getAgent($id)
    {
        return $this->client->getFromAsObject("account/agents/" . (int)$id, Agent::class);
    }

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function setAgent(Agent $agent)
    {
       return $this->client->putInto("account/agents/" . $agent->id, $agent, Agent::class);
    }
}