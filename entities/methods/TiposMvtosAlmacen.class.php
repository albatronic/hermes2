<?php

/**
 * Description of TiposMvtosAlmacen
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class TiposMvtosAlmacen extends TiposMvtosAlmacenEntity {

    public function __toString() {
        return $this->getDescripcion();
    }

}

?>
