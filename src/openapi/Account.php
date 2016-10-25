<?php
namespace naspersclassifieds\realestate\openapi;


use naspersclassifieds\realestate\openapi\model\Advert;
use naspersclassifieds\realestate\openapi\model\AdvertsResult;
use naspersclassifieds\realestate\openapi\query\AccountAdvertsQuery;

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
     * @param AccountAdvertsQuery $query
     * @return AdvertsResult
     */
    public function getAdverts(AccountAdvertsQuery $query = null)
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
}