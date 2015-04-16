<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 24.09.2013 23:03:00
 */

/**
 * @orm:Entity(ArticulosEscandallos)
 */
class ArticulosEscandallos extends ArticulosEscandallosEntity {

    public function __toString() {
        return $this->getId();
    }

}

?>