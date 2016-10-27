<?php
namespace naspersclassifieds\realestate\openapi;


use naspersclassifieds\realestate\openapi\model\Agent;

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
     * Account constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->advertsManager = new AdvertsManager($client);
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
     * @return array
     */
    public function getAgents()
    {
        return $this->client->get('account/agents', [Agent::class]);
    }

    /**
     * @param integer $id
     * @return Agent
     */
    public function getAgent($id)
    {
        return $this->client->get("account/agents/" . (int)$id, Agent::class);
    }

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function setAgent(Agent $agent)
    {
        return $this->client->put("account/agents/" . $agent->id, $agent, Agent::class);
    }

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function addAgent($agent)
    {
        return $this->client->post("account/agents", $agent, Agent::class);
    }
}