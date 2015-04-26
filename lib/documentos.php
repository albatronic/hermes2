<?php

/*
 * Genera el codigo HTML para mostrar los documentos de una entidad
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.05.2011
 */

include "autoloader.inc";

$id = $_GET['id'];
$entidad = $_GET['entidad'];

$objeto = new $entidad($id);
$arrayDocs = $objeto->getDocuments();
unset($objeto);

$tag = '';

if (is_array($arrayDocs)) {
    foreach ($arrayDocs as $doc) {
        if ($doc->getRelativePath()) {
            $fileName = $app['path'] . "/" . $doc->getRelativePath();
            $pathImage = "../" . $doc->getRelativePath();
            $tag .= "<div style='float: left; width: 103px;'>";
            $tag .= "<div>";
            $tag .= "<a target='_blank' title='Documento' href='{$fileName}'>";
            $tag .= "<img src='" . $app['path'] . "/lib/product_thumb.php?img={$pathImage}&amp;w=100&amp;h=100' />";
            $tag .= "</a>";
            $tag .= "</div>";
            $tag .= "<div>";
            $tag .= "<input name='accion' value='Quitar' type='submit' class='Comando' style='width: 100px;' onclick=\"$('#action').val('Documento');$('#documentoBorrar').val('" . $doc->getBaseName() . "')\" />";
            $tag .= "</div>";
            $tag .= "</div>";
        }
    }
    unset($doc);
}
echo $tag;