<?php

/**
 * Define los Roles de Usuarios
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 19-nov-2011
 *
 */
class Roles extends Tipos {

    protected $tipos = array(
        array('Id' => '0', 'Value' => 'Admon'),
        array('Id' => '1', 'Value' => 'Comercial'),
        array('Id' => '2', 'Value' => 'Repartidor'),
        array('Id' => '3', 'Value' => 'Almacenero'),
        array('Id' => '9', 'Value' => 'Super'),
    );

}
