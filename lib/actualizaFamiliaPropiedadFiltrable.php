<?php

/*
 * Actualiza la columna 'Filtrable' de una familia y propiedad
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 26.05.2013
 */

include "autoloader.inc";

$v = $_GET;

$tabla = new FamiliasPropiedades();
$rows = $tabla->cargaCondicion("Id", "IDFamilia='{$v['idFamilia']}' and IDPropiedad='{$v['idPropiedad']}'");
$idRelacion = $rows[0]['Id'];

if (!$idRelacion) {
    // Hacer relacion
    if ($v['valor']) {
        $relacion = new FamiliasPropiedades();
        $relacion->setIDFamilia($v['idFamilia']);
        $relacion->setIDPropiedad($v['idPropiedad']);
        $relacion->setFiltrable(1);
        $relacion->setPublish(1);
        $relacion->create();
    }
} else {
    $relacion = new FamiliasPropiedades($idRelacion);
    $relacion->setFiltrable($v['valor']);
    $relacion->save();
}

unset($tabla);
unset($relacion);

$tag = "";

echo $tag;