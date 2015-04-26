<?php

/*
 * Actualiza la relación entre familias y propiedades
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 09.05.2013
 */

include "autoloader.inc";

$v = $_GET;

$tabla = new FamiliasPropiedades();
$rows = $tabla->cargaCondicion("Id","IDFamilia='{$v['idFamilia']}' and IDPropiedad='{$v['idPropiedad']}'");
$idRelacion = $rows[0]['Id'];

if ($v['valor']) {
    // Hacer relacion
    if (!$idRelacion) {
        $relacion = new FamiliasPropiedades();
        $relacion->setIDFamilia($v['idFamilia']);
        $relacion->setIDPropiedad($v['idPropiedad']);
        $relacion->setPublish(1);
        $relacion->create();
    }
} else {
    // Quitar la relación
    if ($idRelacion) {
        $relacion = new FamiliasPropiedades($idRelacion);
        $relacion->erase();
    }
}

unset($tabla);
unset($relacion);

$tag = "";

echo $tag;