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

$familia = new Familias();
$arbol = $familia->getArbolHijos(array('conArticulos'=>true,'conImagenes'=>true),'','',"MostrarEnTpv='1'", "OrdenTpv ASC");

echo json_encode($arbol);