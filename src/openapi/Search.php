<?php
namespace naspersclassifieds\realestate\openapi;


use naspersclassifieds\realestate\openapi\model\Advert;
use naspersclassifieds\realestate\openapi\model\AdvertsResult;
use naspersclassifieds\realestate\openapi\query\Adverts;

class Search
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Search constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Adverts $query
     * @return AdvertsResult
     */
    public function getAdverts(Adverts $query = null)
    {
        return $this->client->getFromAsObject('adverts' . $query, AdvertsResult::class);
    }

    /**
     * @param integer $id
     * @return Advert
     */
    public function getAdvert($id)
    {
        return $this->client->getFromAsObject('adverts/' . (int)$id, Advert::class);
    }
}