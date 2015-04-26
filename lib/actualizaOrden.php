<?php

/*
 * Actualiza El orden de una entidad
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.12.2013
 */

include "autoloader.inc";

$entidad = $_POST['entidad'];
$orden = $_POST['orden'];
$sortBy = (isset($_POST['sortBy'])) ? $_POST['sortBy'] : "SortOrder";

if ($entidad != '') {
    $objeto = new $entidad();
    $primaryKeyName = $objeto->getPrimaryKeyName();
    foreach ($orden as $key=>$value) {
        $objeto->queryUpdate(array($sortBy=>$key),"{$primaryKeyName}='{$value}'");
    }
    unset($objeto);
}