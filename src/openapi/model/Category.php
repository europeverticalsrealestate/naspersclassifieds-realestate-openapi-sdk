<?php
namespace naspersclassifieds\realestate\openapi\model;


class Category
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var array
     */
    public $names;

    /**
     * @var integer
     */
    public $parent_id;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $path_url;

    /**
     * @var integer
     */
    public $depth;

    /**
     * @var array
     */
    public $parameters;
}