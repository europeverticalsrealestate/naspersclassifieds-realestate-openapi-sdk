<?php
namespace naspersclassifieds\realestate\openapi\query;

class Adverts extends Query
{
    const SORT_BY_CREATION_DATE = 'created_at_first';
    const SORT_BY_LIST_POSITION = 'created_at';
    const SORT_BY_AREA = 'filter_float_m';
    const SORT_BY_PRICE = 'filter_float_price';
    const SORT_BY_PRICE_PER_METER = 'filter_float_price_per_m';

    private $userId;
    private $categoryId;
    private $cityId;
    private $regionId;
    private $latitude;
    private $longitude;
    private $distance;
    private $params;

    /**
     * @param integer $userId
     * @return static
     */
    public function setUser($userId)
    {
        $this->userId = (int)$userId;
        return $this;
    }

    /**
     * @param integer $categoryId
     * @return static
     */
    public function setCategory($categoryId)
    {
        $this->categoryId = (int)$categoryId;
        return $this;
    }

    /**
     * @param integer $cityId
     * @return static
     */
    public function setCity($cityId)
    {
        $this->cityId = (int)$cityId;
        return $this;
    }

    /**
     * @param integer $regionId
     * @return static
     */
    public function setRegion($regionId)
    {
        $this->regionId = (int)$regionId;
        return $this;
    }

    /**
     * @param integer $distance
     * @return static
     */
    public function setDistance($distance)
    {
        $this->distance = (int)$distance;
        return $this;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @return static
     */
    public function setLatLng($latitude, $longitude)
    {
        $this->latitude = (float)$latitude;
        $this->longitude = (float)$longitude;
        return $this;
    }

    /**
     * @param string $name
     * @param string|integer|float $value
     * @return static
     */
    public function setParam($name, $value)
    {
        if (is_scalar($value)) {
            $this->params[$name] = $value;
        }
        return $this;
    }

    /**
     * @param string $name
     * @param float $value
     * @return static
     */
    public function setFromParam($name, $value)
    {
        $this->params[$name] = ['from', (float)$value];
        return $this;
    }

    /**
     * @param string $name
     * @param float $value
     * @return static
     */
    public function setToParam($name, $value)
    {
        $this->params[$name] = ['to', (float)$value];
        return $this;
    }

    /**
     * @param string $name
     * @param float $from
     * @param float $to
     * @return static
     */
    public function setRangeParam($name, $from, $to)
    {
        $this->params[$name] = ['range', (float)$from, (float)$to];
        return $this;
    }

    /**
     * @param string $name
     * @param array $values
     * @return static
     */
    public function setMultiOptionParam($name, $values)
    {
        if (!is_array($values)){
            return $this;
        }

        $filteredValues = [];
        foreach ($values as $value) {
            if (is_scalar($value)) {
                $filteredValues[] = $value;
            }
        }

        if (empty($filteredValues)) {
            return $this;
        }

        $this->params[$name] = array_merge(['all'], $filteredValues);
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

        if (!empty($this->params)) {
            $filters['params'] = $this->params;
        }

        if (!empty($filters)) {
            $query .= ($query ? '&' : '?') . 'fq=' . json_encode($filters);
        }

        return $query;
    }
}
