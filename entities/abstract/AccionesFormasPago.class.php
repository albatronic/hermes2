<?php
/**
 * Define los acciones a realizar con forma de pago
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 01-jul-2014
 *
 */

class AccionesFormasPago extends Tipos {

    protected $tipos = array(
        array('Id' => '0', 'Value' => 'Ninguna'),
        array('Id' => '1', 'Value' => 'No pedir dirección de envío'),
        array('Id' => '2', 'Value' => 'Ir a la pasarela de pago'),     
    );
}