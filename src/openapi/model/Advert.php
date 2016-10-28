<?php
namespace naspersclassifieds\realestate\openapi\model;


class Advert
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $external_id;

    /**
     * @var integer
     */
    public $user_id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $valid_to;

    /**
     * @var string
     */
    public $description;

    /**
     * @var integer
     */
    public $category_id;

    /**
     * @var integer
     */
    public $region_id;

    /**
     * @var integer
     */
    public $city_id;

    /**
     * @var integer
     */
    public $district_id;

    /**
     * @var array
     */
    public $city;

    /**
     * @var array
     */
    public $district;

    /**
     * @var array
     */
    public $coordinates;

    /**
     * @var string
     */
    public $advertiser_type;

    /**
     * @var array
     */
    public $contact;

    /**
     * @var array
     */
    public $params;

    /**
     * @var array
     */
    public $photos;

    /**
     * @var integer
     */
    public $image_collection_id;

    /**
     * @var string
     */
    public $street_name;

    /**
     * @var array
     */
    public $agent;
}