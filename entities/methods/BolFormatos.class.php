<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 11.07.2013 19:17:44
 */

/**
 * @orm:Entity(BolFormatos)
 */
class BolFormatos extends BolFormatosEntity {

    public function __toString() {
        return $this->getId();
    }

}

?>