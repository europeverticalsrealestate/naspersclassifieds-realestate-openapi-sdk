<?php
namespace naspersclassifieds\realestate\openapi\model;

class AdvertsResult
{
    /**
     * @var array
     */
    public $results;

    /**
     * @var boolean
     */
    public $is_last_page;

    /**
     * @var boolean
     */
    public $is_first_page;

    /**
     * @var integer
     */
    public $current_page;

    /**
     * @var integer
     */
    public $total_pages;

    /**
     * @var integer
     */
    public $current_elements;

    /**
     * @var integer
     */
    public $total_elements;
}