<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.10.2013 14:52:11
 */

/**
 * @orm:Entity(Favoritos)
 */
class Favoritos extends FavoritosEntity {

    public function __toString() {
        return $this->getId();
    }

}

?>