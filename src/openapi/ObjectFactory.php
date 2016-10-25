<?php
namespace naspersclassifieds\realestate\openapi;


use naspersclassifieds\realestate\openapi\model\Advert;
use naspersclassifieds\realestate\openapi\model\AdvertsResult;

class ObjectFactory
{
    private $mappings = [
        AdvertsResult::class => [
            'results' => [
                'class' => Advert::class,
                'isArray' => true
            ]
        ]
    ];

    /**
     * @var string
     */
    private $className;

    /**
     * DataFactory constructor.
     * @param $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    public function build(array $object){
        $newObj = new $this->className;
        foreach ($newObj as $fieldName => &$fieldValue) {
            if (!isset($object[$fieldName])) {
                continue;
            }
            if (empty($this->mappings[$this->className][$fieldName])) {
                $fieldValue = $object[$fieldName];
                continue;
            }
            $fieldMapping = $this->mappings[$this->className][$fieldName];
            $factory = new ObjectFactory($fieldMapping['class']);
            $fieldValue = (empty($fieldMapping['isArray']))
                ? $factory->build($object[$fieldName])
                : $factory->buildMany($object[$fieldName]);
        }
        return $newObj;
    }

    public function buildMany(array $data) {
        $result = [];
        foreach ($data as $object) {
            $result[] = $this->build($object);
        }
        return $result;
    }
}