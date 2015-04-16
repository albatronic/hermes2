<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 09.05.2013 19:32:19
 */

/**
 * @orm:Entity(ErpFamiliasPropiedades)
 */
class FamiliasPropiedades extends FamiliasPropiedadesEntity {

    public function __toString() {
        return $this->getId();
    }

}

?>