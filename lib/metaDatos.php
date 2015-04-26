<?php

/*
 * CREA O BORRA METADATOS
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 21.09.2013
 */

include "autoloader.inc";

$v = $_GET;

$entidad = $v['entidad'];
$metadato = $v['metadato'];
$accion = $v['accion'];
$idEntidad = $v['idEntidad'];

$tag = 0;
switch ($accion) {
    case 'B':
        $meta = new CpanMetaData();
        $meta->queryDelete("Entity='{$entidad}' and Name='{$metadato}'");
        $tag = count($meta->getErrores()) == 0;
        unset($meta);
        break;
    case 'C':
        $meta = new CpanMetaData();
        $rows = $meta->cargaCondicion("Id", "Entity='{$entidad}' and Name='{$metadato}'");
        if (count($rows) == 0) {
            $meta->setEntity($entidad);
            $meta->setIdEntity($idEntidad);
            $meta->setName($metadato);
            $meta->create();
            $tag = (count($meta->getErrores()) == 0);
        } else $tag = 2;
        unset($meta);
        break;
}

echo $tag;