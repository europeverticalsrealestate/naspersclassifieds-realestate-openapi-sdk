<?php
namespace naspersclassifieds\realestate\openapi;


use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
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
     * @throws OpenApiException
     */
    public function getCategories()
    {
        return $this->client->getFrom('categories')->results;
    }

    /**
     * @param integer $id category id
     * @return stdClass
     * @throws OpenApiException
     */
    public function getCategory($id)
    {
        return $this->client->getFrom('categories/' . (int)$id);
    }

    /**
     * @return array
     * @throws OpenApiException
     */
    public function getCities()
    {
        return $this->client->getFrom('cities')->results;
    }

    /**
     * @param integer $id city id
     * @return stdClass
     * @throws OpenApiException
     */
    public function getCity($id)
    {
        return $this->client->getFrom('cities/' . (int)$id);
    }

    /**
     * @return array
     * @throws OpenApiException
     */
    public function getRegions()
    {
        return $this->client->getFrom('regions')->results;
    }

    /**
     * @param integer $id
     * @return stdClass
     * @throws OpenApiException
     */
    public function getRegion($id)
    {
        return $this->client->getFrom('regions/' . (int)$id);
    }

    /**
     * @return array
     * @throws OpenApiException
     */
    public function getSubRegions()
    {
        return $this->client->getFrom('subregions')->results;
    }

    /**
     * @param integer $id
     * @return stdClass
     * @throws OpenApiException
     */
    public function getSubRegion($id)
    {
        return $this->client->getFrom('subregions/' . (int)$id);
    }
}