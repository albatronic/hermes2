<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 12.07.2014 20:31:56
 */

/**
 * @orm:Entity(ErpPedidosWebLineas)
 */
class PedidosWebLineas extends PedidosWebLineasEntity {

    protected $Publish = '1';
    
    public function __toString() {
        return ($this->IDLinea) ? $this->IDLinea : '';
    }

}
