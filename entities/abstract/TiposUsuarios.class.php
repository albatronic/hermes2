<?php
/**
 * Define los tipos de Usuarios desde el punto
 * de vista del origen del acceso al ERP
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 19-nov-2011
 *
 */

class TiposUsuarios extends Tipos {

    protected $tipos = array(
        array('Id' => '0', 'Value' => 'Usuario Erp'),
        array('Id' => '1', 'Value' => 'Usuario Web'),
    );
}

?>
