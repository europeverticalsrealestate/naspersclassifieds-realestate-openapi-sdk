<?php
namespace naspersclassifieds\realestate\openapi\query;


class Adverts extends Query
{
    const SORT_BY_CREATED_AT = 'created_at';

    private $userId;
    private $categoryId;
    private $cityId;
    private $regionId;
    private $latitude;
    private $longitude;
    private $distance;

    /**
     * @param $userId
     * @return static
     */
    public function setUser($userId)
    {
        $this->userId = (int)$userId;
        return $this;
    }

    /**
     * @param $categoryId
     * @return static
     */
    public function setCategory($categoryId)
    {
        $this->categoryId = (int)$categoryId;
        return $this;
    }

    /**
     * @param $cityId
     * @return static
     */
    public function setCity($cityId)
    {
        $this->cityId = (int)$cityId;
        return $this;
    }

    /**
     * @param $regionId
     * @return static
     */
    public function setRegion($regionId)
    {
        $this->regionId = (int)$regionId;
        return $this;
    }

    /**
     * @param $distance
     * @return static
     */
    public function setDistance($distance)
    {
        $this->distance = (int)$distance;
        return $this;
    }

    public function setLatLng($latitude, $longitude)
    {
        $this->latitude = (float)$latitude;
        $this->longitude = (float)$longitude;
        return $this;
    }

    public function __toString()
    {
        $query = parent::__toString();

        $filters = [];

        if ($this->userId) {
            $filters['user_id'] = $this->userId;
        }

        if ($this->categoryId) {
            $filters['category_id'] = $this->categoryId;
        }

        if ($this->cityId) {
            $filters['city_id'] = $this->cityId;
        }

        if ($this->regionId) {
            $filters['region_id'] = $this->regionId;
        }

        if ($this->latitude && $this->longitude) {
            $filters['latitude'] = $this->latitude;
            $filters['longitude'] = $this->longitude;
        }

        if ($this->distance) {
            $filters['distance'] = $this->distance;
        }

        if (!empty($filters)) {
            $query .= ($query ? '&' : '?') . 'fq=' . json_encode($filters);
        }

        return $query;
    }
}