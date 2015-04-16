<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 13.07.2014 17:39:52
 */

/**
 * @orm:Entity(ErpAfiliados)
 */
class Afiliados extends AfiliadosEntity {

    public function __toString() {
        return ($this->IDAfiliado) ? $this->IDAfiliado : '';
    }

}
