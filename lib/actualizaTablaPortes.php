<?php

/*
 * Actualiza la tabla de portes
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 29.03.2013
 */

include "autoloader.inc";

$v = $_GET;

$tabla = new TablaPortes();
$rows = $tabla->cargaCondicion("Id","IDZona='{$v['idZona']}' and IDAgencia='{$v['idAgencia']}' and Kilos='{$v['kilos']}'");
$id = $rows[0]['Id'];

if ($id!=0) {
    // Ya existía, actualizo
    $tabla = new TablaPortes($id);
    $tabla->setImporte($v['importe']);
    $tabla->save();
} else {
    // No existe, creo
    $tabla->setIDAgencia($v['idAgencia']);
    $tabla->setIDZona($v['idZona']);
    $tabla->setKilos($v['kilos']);
    $tabla->setImporte($v['importe']);
    $tabla->create();
}

$tag = $tabla->getErrores();
$tag = $tag[0];
unset($tabla);

echo $tag;