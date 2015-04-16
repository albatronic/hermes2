<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 26.01.2014 21:12:08
 */

/**
 * @orm:Entity(EjerciciosContables)
 */
class EjerciciosContables extends EjerciciosContablesEntity {

    public function __toString() {
        return $this->getId();
    }

}

?>