<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 19.07.2014 02:26:08
 */

/**
 * @orm:Entity(ErpAvisadorStock)
 */
class AvisadorStock extends AvisadorStockEntity {

    public function __toString() {
        return ($this->Id) ? $this->Id : '';
    }

}
