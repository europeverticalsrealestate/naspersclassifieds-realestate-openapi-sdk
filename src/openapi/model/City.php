<?php
namespace naspersclassifieds\realestate\openapi\model;

class City
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $text;

    /**
     * @var double
     */
    public $lon;

    /**
     * @var double
     */
    public $lat;

    /**
     * @var double
     */
    public $radius;

    /**
     * @var integer
     */
    public $zoom;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $url;

    /**
     * @var integer
     */
    public $districts_city_id;

    /**
     * @var integer
     */
    public $region_id;
}