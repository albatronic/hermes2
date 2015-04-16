<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 21.09.2013 15:15:26
 */

/**
 * @orm:Entity(CpanMetaData)
 */
class CpanMetaData extends CpanMetaDataEntity {

    public function __toString() {
        return $this->getId();
    }

    public function create() {
        
        $this->setPublish(true);
        
        return parent::create();
    }
}

?>