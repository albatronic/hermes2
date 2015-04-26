<?php

/*
 * ACTUALIZA UNA COLUMNA DE UNA ENTIDAD
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.05.2011
 */

include "autoloader.inc";

$v = $_GET;

$entidad = $v['entidad'];
$idEntidad = $v['idEntidad'];
$columna = $v['columna'];

// Actualiza la columna en el idioma actual
$objeto = new $entidad($idEntidad);
$objeto->{"set$columna"}($v['valor']);
if ($objeto->save() && ($columna == 'Publish' || $columna == 'Privacy')) {
    // Actualiza la url amigable
    $urlAmigable = CpanUrlAmigables::sincroniza($objeto);
    // Actualiza la tabla de búsquedas
    $variables = new CpanVariables("Mod", "Env", $entidad);
    if ($variables->getNode('searchable')) {
        $search = new CpanSearch();
        $search->actualiza($objeto);
        unset($search);
    }
}
unset($objeto);

// Actualiza la columna en el resto de idiomas
if ($_SESSION['idiomas']['actual'] == 0) {

    // Recorro los idiomas adicionales
    foreach ($_SESSION['idiomas']['disponibles'] as $key => $value) {
        if ($key > 0) {
            $_SESSION['idiomas']['actual'] = $key;
            $objeto = new $entidad($idEntidad);
            $objeto->{"set$columna"}($v['valor']);
            if ($objeto->save() && ($columna == 'Publish' || $columna == 'Privacy')) {
                // Actualiza la url amigable
                $urlAmigable = CpanUrlAmigables::sincroniza($objeto);
                // Actualiza la tabla de búsquedas
                $variables = new CpanVariables("Mod", "Env", $entidad);
                if ($variables->getNode('searchable')) {
                    $search = new CpanSearch();
                    $search->actualiza($objeto);
                    unset($search);
                }
            }
            unset($objeto);
        }
    }
    $_SESSION['idiomas']['actual'] = 0;
}

$tag = "";

echo $tag;