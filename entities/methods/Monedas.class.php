<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 02.02.2014 01:15:20
 */

/**
 * @orm:Entity(ErpMonedas)
 */
class Monedas extends MonedasEntity {

    public function __toString() {
        return $this->getMoneda();
    }

}

?>