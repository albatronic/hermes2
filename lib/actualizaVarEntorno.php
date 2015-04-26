<?php

/*
 * ACTUALIZA LA VISIBILIDAD DE UNA COLUMNA
 * CAMBIA LA VARIABLE DE ENTORNO 'visible'
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.10.2013
 */

include "autoloader.inc";

$valores = explode("_", $_GET['entidadColumnaPropiedad']);
$modulo = $valores[0];
$columna = $valores[1];
$propiedad = $valores[2];

$var = new CpanVariables('Mod','Env',$modulo);
$atributosColumna = $var->getColumn($columna);
$atributosColumna[$propiedad] = $_GET['valor'];
$var->setColumn($columna, $atributosColumna);
$var->save();
unset($var);

$tag = "";

echo $tag;