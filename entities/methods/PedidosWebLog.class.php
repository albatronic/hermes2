<?php

/**
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @date 20.09.2014 17:49:23
 */

/**
 * @orm:Entity(ErpPedidosWebLog)
 */
class PedidosWebLog extends PedidosWebLogEntity {

    public function __toString() {
        return ($this->Id) ? $this->Id : '';
    }

    public function create() {

        $this->Fecha = date('Y-m-d H:i:s');
        $this->IpAddress = $_SERVER['REMOTE_ADDR'];

        return parent::create();
    }

}
