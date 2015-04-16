<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 13.11.2013 23:33:08
 */

/**
 * @orm:Entity(ErpFavoritosTpv)
 */
class FavoritosTpv extends FavoritosTpvEntity {

    public function __toString() {
        return $this->getId();
    }

}

?>