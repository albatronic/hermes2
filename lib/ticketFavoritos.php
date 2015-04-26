<?php

/**
 * INSERTA UN LOTE NUEVO EN LA TABLA DE LOTES
 * Y CREA UN TAG <SELECT> CON LOS LOTES DEL ARTICULO INDICADO
 * INCLUYENDO EL QUE ACABA DE CREAR.
 *
 * SE HACEN VALIDACIONES EN CUANTO A LA IDONEIDAD DE LAS FECHAS
 *
 * LOS PARAMETROS POST QUE RECIBE SON:
 *
 * idArticulo   -> EL ID DEL ARTICULO
 * lote         -> EL TEXTO DEL LOTE
 * fFabricacion -> LA FECHA DE FABRICACION
 * fCaducidad   -> LA FECHA DE CADUCIDAD
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.05.2011
 */

include "autoloader.inc";

$parametros = $_REQUEST['parametros'];
$accion = $parametros['accion'];
$datos = $parametros['datos'];


switch ($accion) {
    case 'add':
        $fav = new FavoritosTpv();
        $rows = $fav->cargaCondicion("Id", "IDTpv='{$datos['IDTpv']}' and IDArticulo='{$datos['IDArticulo']}'");
        if ($rows[0]['Id'] == '') {
            $fav->setIDTpv($datos['IDTpv']);
            $fav->setIDArticulo($datos['IDArticulo']);
            $fav->setDescripcion($datos['Descripcion']);
            $idCreado = $fav->create();
            $errores = $fav->getErrores();
            $alertas = $fav->getAlertas();
        }
        unset($fav);
        break;

    case 'remove':
        $fav = new FavoritosTpv($datos['Id']);
        $fav->erase();
        $errores = $fav->getErrores();
        $alertas = $fav->getAlertas();
        unset($fav);
        break;
}

$status = 'ok';
if (count($errores))
    $status = "error";
if (count($alertas))
    $status = "alerta";

$resultado = array(
    'status' => $status,
    'accion' => $accion,
    'errores' => $errores,
    'alertas' => $alertas,
    'idCreado' => $idCreado,
);

$tag = json_encode($resultado);

echo $tag;