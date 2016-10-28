<?php
namespace naspersclassifieds\realestate\openapi;

use naspersclassifieds\realestate\openapi\model\Advert;
use naspersclassifieds\realestate\openapi\model\AdvertsResult;
use naspersclassifieds\realestate\openapi\model\Agent;

class ObjectFactory
{
    private $mappings = [
        AdvertsResult::class => [
            'results' => [
                'class' => Advert::class,
                'isArray' => true
            ]
        ],
        Advert::class => [
            'agent' => [
                'class' => Agent::class
            ]
        ]
    ];

    /**
     * @var string
     */
    private $className;

    private $isArray = false;

    /**
     * DataFactory constructor.
     * @param $class
     */
    public function __construct($class)
    {
        if (is_array($class)){
            $this->isArray = true;
            $class = array_shift($class);
        }
        $this->className = $class;
    }

    public function build(array $data) {
        if ($this->isArray) {
            return $this->buildMany($data);
        }
        return $this->buildOne($data);
    }

    private function buildOne(array $data){
        $newObj = new $this->className;
        foreach ($newObj as $fieldName => &$fieldValue) {
            if (!isset($data[$fieldName])) {
                continue;
            }
            if (empty($this->mappings[$this->className][$fieldName])) {
                $fieldValue = $data[$fieldName];
                continue;
            }
            $fieldMapping = $this->mappings[$this->className][$fieldName];
            $fieldClass = empty($fieldMapping['isArray']) ? $fieldMapping['class'] : [$fieldMapping['class']];
            $factory = new ObjectFactory($fieldClass);
            $fieldValue = $factory->build($data[$fieldName]);
        }
        return $newObj;
    }

    private function buildMany(array $data) {
        $result = [];
        if (isset($data['results']) && is_array($data['results'])) {
            $data = $data['results'];
        }
        foreach ($data as $object) {
            $result[] = $this->buildOne($object);
        }
        return $result;
    }
}
