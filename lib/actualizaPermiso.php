<?php

/*
 * ACTUALIZA LOS PERMISOS DE ACCESO A LAS APPS DEL ERP
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 14.04.2013
 */

include "autoloader.inc";

$v = $_GET;
$filtro = "IdPerfil='{$v['idPerfil']}' and NombreModulo='{$v['nombreModulo']}'";
$permiso = new Permisos();
$rows = $permiso->cargaCondicion("Id,Funcionalidades", $filtro);
$idPermiso = $rows[0]['Id'];

if ($idPermiso) {
    if ($v['valor'] == 0) {
        // Quitar permiso
        $permisos = str_replace($v['permiso'], "", $rows[0]['Funcionalidades']);
        // Quitar la eventual coma inicial
        if (substr($permisos,0,1) == ",")
            $permisos = substr($permisos, 1);
        // Quitar dobles comas
        $permisos = str_replace(",,", ",", $permisos);
        // Quito la eventual coma final
        if (substr($permisos, -1) == ",")
            $permisos = substr($permisos, 0, -1);
    } else {
        // Poner permiso
        if (strpos($rows[0]['Funcionalidades'], $v['permiso']) === false)
            $permisos = $rows[0]['Funcionalidades'] . "," . $v['permiso'];
        // Quitar la eventual coma inicial
        if (substr($permisos,0,1) == ",")
            $permisos = substr($permisos, 1);
    }
    $permiso = new Permisos($idPermiso);
    $permiso->setFuncionalidades($permisos);
    $permiso->save();
}

unset($permiso);

$tag = "";

echo $tag;