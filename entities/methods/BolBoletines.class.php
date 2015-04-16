<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 06.07.2013 15:53:44
 */

/**
 * @orm:Entity(BolBoletines)
 */
class BolBoletines extends BolBoletinesEntity {

    public function __toString() {
        return $this->getId();
    }
}

?>