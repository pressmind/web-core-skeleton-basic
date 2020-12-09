<?php

use Pressmind\ORM\Object\MediaObject;
use Pressmind\Search\AbstractTemplate;

/**
 * Class ApiTemplateExample
 * @property MediaObject $_object
 */
class ApiTemplateExample extends AbstractTemplate {
    public function render() {
        return [
            'code' => $this->_object->code,
            'id' => $this->_object->getId(),
            'name' => $this->_object->name,
            'cheapest_price' => $this->_object->getCheapestPrice(),
            'data' => $this->_object->getDataForLanguage()
        ];
    }
}
