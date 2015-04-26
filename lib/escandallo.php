<?php

/*
 * DEVUELVE EL ESCANDALLO DE UN ARTICULO EN EL FORMATO
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

$idArticulo = $v['idArticulo'];
$formato = strtoupper($v['formato']);

$articulo = new Articulos($idArticulo);
$arrayEscandallo = $articulo->getEscandallo();

switch ($formato) {
    case '':
    case 'JSON':
        $tag = json_encode($arrayEscandallo);
        break;
    default:
        $tag = "";
        break;
}

echo $tag;