<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 09.03.2014 11:20:28
 */

/**
 * @orm:Entity(ErpFavoritosWeb)
 */
class FavoritosWeb extends FavoritosWebEntity {

    public function __toString() {
        return ($this->Id) ? $this->Id : '';
    }

}

?>