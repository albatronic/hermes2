<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 09.05.2013 23:00:45
 */

/**
 * @orm:Entity(ErpArticulosPropiedades)
 */
class ArticulosPropiedades extends ArticulosPropiedadesEntity {

    public function __toString() {
        return $this->getId();
    }

}

?>