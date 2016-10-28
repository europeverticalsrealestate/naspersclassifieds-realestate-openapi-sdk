<?php
namespace naspersclassifieds\realestate\openapi;

use naspersclassifieds\realestate\openapi\model\Agent;

class AgentsManger
{
    /**
     * @var Client
     */
    private $client;

    /**
     * AgentsManger constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
    public function updateAgent(Agent $agent)
    {
        return $this->client->update("account/agents/" . (int)$agent->id, $agent, Agent::class);
    }

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function createAgent($agent)
    {
        return $this->client->post("account/agents", $agent, Agent::class);
    }

    /**
     * @param integer $id
     */
    public function deleteAgent($id)
    {
        $this->client->delete("account/agents/" . (int)$id);
    }
}
