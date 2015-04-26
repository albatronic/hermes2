<?php

/*
 * Actualiza la relación N a M entre entidades e id de entidades
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 03.02.2013
 */

include "autoloader.inc";

$v = $_GET;

$relacion = new CpanRelaciones();
$idRelacion = $relacion->getIdRelacion($v['entidadOrigen'], $v['idOrigen'], $v['entidadDestino'], $v['idDestino']);

if ($v['onOff']) {
    // Hacer relacion
    if (!$idRelacion) {
        $relacion = new CpanRelaciones();
        $relacion->setEntidadOrigen($v['entidadOrigen']);
        $relacion->setIdEntidadOrigen($v['idOrigen']);
        $relacion->setEntidadDestino($v['entidadDestino']);
        $relacion->setIdEntidadDestino($v['idDestino']);
        $relacion->setPublish(1);
        $relacion->create();
    }
} else {
    // Quitar la relación
    if ($idRelacion) {
        $relacion = new CpanRelaciones($idRelacion);
        $relacion->erase();
    }
}

unset($relacion);

$tag = "";

echo $tag;