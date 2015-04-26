<?php

/*
 * DEVUELVE UN OBJETO DE DATOS EN EL FORMATO
 * INDICADO EN EL PARÁMETRO 'formato'
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 24.09.2013
 */

include "autoloader.inc";

$v = $_GET;

$entidad = $v['entidad'];
$idEntidad = $v['idEntidad'];
$formato = strtoupper($v['formato']);
$columna = $v['columna'];
$valor = $v['valor'];

// Si en columna viene valor es que tengo que
// buscar por columna y valor y no por id
if (isset($v['columna'])) {
    $objeto = new $entidad();
    $objeto = $objeto->find($columna, $valor);
}
else {
    $objeto = new $entidad($idEntidad);
}

switch ($formato) {
    case '':
    case 'JSON':
        $tag = $objeto->getJson();
        break;
    default:
        $tag = "";
        break;
}

unset($objeto);

echo $tag;