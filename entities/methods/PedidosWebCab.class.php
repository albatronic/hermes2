<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.07.2014 20:29:53
 */

/**
 * @orm:Entity(ErpPedidosWebCab)
 */
class PedidosWebCab extends PedidosWebCabEntity {

    protected $Publish = '1';
    
    public function __toString() {
        return ($this->IDPedido) ? $this->IDPedido : '';
    }

}
