<?php

/**
 * Description of Sucursales
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class Sucursales extends SucursalesEntity {

    /**
     * Devuelve el nombre de la empresa
     * @return string
     */
    public function __toString() {
        return $this->getNombre();
    }

    /**
     * Devuelve un array con las sucursales de la empresa en curso
     * a los que tiene acceso el usuario $idUsuario.
     *
     * Si no se indica $idUsuario se toma el usuario en curso.
     *
     * @param integer $idUsuario
     * @return array Array de sucursales
     */
    public function getSucursalesUsuario($idUsuario = '', $opcionTodas=true) {

        if ($idUsuario == '')
            $idUsuario = $_SESSION['usuarioPortal']['Id'];

        $usuario = new Agentes($idUsuario);
        $rows = $usuario->getSucursales($_SESSION['emp'],$opcionTodas);
        unset($usuario);

        return $rows;
    }

    /**
     * Devuelve un array con los tpvs de la sucursal
     *
     * @return array Tpvs de la sucursal
     */
    public function getTpvs() {

        $tpv = new Tpvs();
        $rows = $tpv->fetchAll($this->IDSucursal);
        unset($tpv);

        return $rows;
    }

}

?>
