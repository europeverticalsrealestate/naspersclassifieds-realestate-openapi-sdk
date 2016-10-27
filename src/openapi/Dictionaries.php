<?php
namespace naspersclassifieds\realestate\openapi;


use naspersclassifieds\realestate\openapi\model\Category;
use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use naspersclassifieds\realestate\openapi\model\City;
use naspersclassifieds\realestate\openapi\model\Region;
use naspersclassifieds\realestate\openapi\model\SubRegion;
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
        return $this->client->getFrom('categories', [Category::class]);
    }

    /**
     * @param integer $id category id
     * @return stdClass
     * @throws OpenApiException
     */
    public function getCategory($id)
    {
        return $this->client->getFrom('categories/' . (int)$id , Category::class);
    }

    /**
     * @return array
     * @throws OpenApiException
     */
    public function getCities()
    {
        return $this->client->getFrom('cities', [City::class]);
    }

    /**
     * @param integer $id city id
     * @return City
     * @throws OpenApiException
     */
    public function getCity($id)
    {
        return $this->client->getFrom('cities/' . (int)$id , City::class);
    }

    /**
     * @return array
     * @throws OpenApiException
     */
    public function getRegions()
    {
        return $this->client->getFrom('regions', [Region::class]);
    }

    /**
     * @param integer $id
     * @return Region
     * @throws OpenApiException
     */
    public function getRegion($id)
    {
        return $this->client->getFrom('regions/' . (int)$id , Region::class);
    }

    /**
     * @return array
     * @throws OpenApiException
     */
    public function getSubRegions()
    {
        return $this->client->getFrom('subregions', [SubRegion::class]);
    }

    /**
     * @param integer $id
     * @return SubRegion
     * @throws OpenApiException
     */
    public function getSubRegion($id)
    {
        return $this->client->getFrom('subregions/' . (int)$id , SubRegion::class);
    }
}