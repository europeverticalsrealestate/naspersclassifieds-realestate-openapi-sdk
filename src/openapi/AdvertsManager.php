<?php
namespace naspersclassifieds\realestate\openapi;


use naspersclassifieds\realestate\openapi\model\Advert;
use naspersclassifieds\realestate\openapi\model\AdvertsResult;
use naspersclassifieds\realestate\openapi\query\AccountAdverts;

class AdvertsManager
{

    /**
     * @var Client
     */
    private $client;

    /**
     * AdvertsManagement constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param AccountAdverts $query
     * @return AdvertsResult
     */
    public function getAdverts(AccountAdverts $query = null)
    {
        return $this->client->get('account/adverts' . $query, AdvertsResult::class);
    }

    /**
     * @param integer $id
     * @return Advert
     */
    public function getAdvert($id)
    {
        return $this->client->get('account/adverts/' . (int)$id, Advert::class);
    }
}