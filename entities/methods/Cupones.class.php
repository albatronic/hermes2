<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.04.2014 18:24:51
 */

/**
 * @orm:Entity(ErpCupones)
 */
class Cupones extends CuponesEntity {

    public function __toString() {
        return ($this->Cupon) ? $this->Cupon : '';
    }

    /**
     * Valida la vigencia del cupón $cupon
     * @param type $cupon
     * @return boolean TRUE si está vigente
     */
    public function validaCupon($cupon) {

        $hoy = date("Y-m-d");

        $cupones = new $this($cupon);
        $ok = ($cupones->getPublish()->getIDTipo() == '1' && $cupones->getActiveFrom('aaaammdd') <= "{$hoy}" && $cupones->getActiveTo('aaaammdd') >= "{$hoy}" && ($cupones->getLimiteUsos() == 0 || $cupones->getNumeroUsos() < $cupones->getLimiteUsos()));
        unset($cupones);
        
        return $ok;
    }

}
