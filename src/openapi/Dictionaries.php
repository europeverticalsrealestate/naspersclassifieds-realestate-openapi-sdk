<?php
/**
 * Created by PhpStorm.
 * User: jaroslaw.wieczorek
 * Date: 2016-10-21
 * Time: 10:24
 */

namespace naspersclassifieds\realestate\openapi;


use GuzzleHttp\Exception\RequestException;
use stdClass;

class Dictionaries
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Dictionaries constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     * @throws RequestException
     */
    public function getCategories()
    {
        return $this->client->getFrom('categories')->results;
    }

    /**
     * @param integer $id category id
     * @return stdClass
     * @throws RequestException
     */
    public function getCategory($id)
    {
        return $this->client->getFrom('categories/' . (int)$id);
    }

    /**
     * @return array
     * @throws RequestException
     */
    public function getCities()
    {
        return $this->client->getFrom('cities')->results;
    }

    /**
     * @param integer $id city id
     * @return stdClass
     * @throws RequestException
     */
    public function getCity($id)
    {
        return $this->client->getFrom('cities/' . (int)$id);
    }

    /**
     * @return array
     * @throws RequestException
     */
    public function getRegions()
    {
        return $this->client->getFrom('regions')->results;
    }

    /**
     * @param integer $id
     * @return stdClass
     * @throws RequestException
     */
    public function getRegion($id)
    {
        return $this->client->getFrom('regions/' . (int)$id);
    }

    /**
     * @return array
     * @throws RequestException
     */
    public function getSubRegions()
    {
        return $this->client->getFrom('subregions')->results;
    }

    /**
     * @param integer $id
     * @return stdClass
     * @throws RequestException
     */
    public function getSubRegion($id)
    {
        return $this->client->getFrom('subregions/' . (int)$id);
    }
}