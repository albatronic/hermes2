<?php

/*
 * Actualiza la tripleta articulo-propiedad-valor
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 10.05.2013
 */

include "autoloader.inc";

$v = $_GET;

$relacion = new ArticulosPropiedades();
$rows = $relacion->cargaCondicion("Id", "IDArticulo='{$v['idArticulo']}' and IDPropiedad='{$v['idPropiedad']}'");
$idRelacion = $rows[0]['Id'];

if (!$idRelacion) {
    // Hacer relacion       
    $relacion = new ArticulosPropiedades();
    $relacion->setIDArticulo($v['idArticulo']);
    $relacion->setIDPropiedad($v['idPropiedad']);
    $relacion->setIDValor($v['idValor']);
    $relacion->setPublish(1);
    $relacion->create();
} else {
    if ($v['idValor'] > 0) {
        // Actualizar la relación
        $relacion = new ArticulosPropiedades($idRelacion);
        $relacion->setIDValor($v['idValor']);
        $relacion->save();
    } else {
        // Borrar la relación
        $relacion = new ArticulosPropiedades($idRelacion);
        $relacion->erase();
    }
}

$tag = $relacion->getErrores();
$tag = $tag[0];

unset($relacion);

echo $tag;