<?php
/**
 * Define los estados de los Pedidos Web
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 12-jul-2014
 *
 */

class EstadosPedidosWeb extends Tipos {

    protected $tipos = array(
        array('Id' => '0', 'Value' => 'En tramite'),
        array('Id' => '1', 'Value' => 'Anulado'),
        array('Id' => '2', 'Value' => 'Confirmado Pagado'),
        array('Id' => '3', 'Value' => 'Confirmado Pte. Pago'),
        array('Id' => '4', 'Value' => 'Albaranado Parcial'),
        array('Id' => '5', 'Value' => 'Albaranado Total'),
    );
}
