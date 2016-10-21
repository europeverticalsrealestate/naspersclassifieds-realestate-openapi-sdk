<?php
namespace naspersclassifieds\realestate\openapi;


class ObjectFactory
{
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
            if (isset($object[$fieldName])) {
                $fieldValue = $object[$fieldName];
            }
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