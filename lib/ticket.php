<?php

/**
 * GESTION AJAX DE LAS LINEAS DE TICKETS
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.05.2011
 */

include "autoloader.inc";

$parametros = $_REQUEST['parametros'];
$accion = $parametros['accion'];
$datos = $parametros['datos'];
$pvpConIva = $parametros['pvpConIva'];


switch ($accion) {
    case 'crear':     
        if ($pvpConIva == '1') {
            // El precio viene con iva, calculo la base
            $datos['Precio'] = $datos['Precio'] * 100 / (100 + $datos['Iva']);
        }

        $linea = new AlbaranesLineas();
        $linea->bind($datos);
        $linea->setRecargo("0");
        if ($linea->valida(array())) {
            $id = $linea->create();
            if (!$id) {
                $errores = $linea->getErrores();
            } else {
                $alertas = $linea->getAlertas();
                $linea = new AlbaranesLineas($id);
            }
        }
        break;

    case 'borrar':
        $linea = new AlbaranesLineas($datos['IDLinea']);
        if (!$linea->erase()) {
            $errores = $linea->getErrores();
        } else {
            $linea = new AlbaranesLineas();
            $alertas = $linea->getAlertas();
        }
        break;

    case 'guardar':
        if ($pvpConIva == '1') {
            // El precio viene con iva, calculo la base
            $datos['Precio'] = $datos['Precio'] * 100 / (100 + $datos['Iva']);
        }        

        $linea = new AlbaranesLineas($datos['IDLinea']);
        $linea->bind($datos);
        if ($linea->valida(array())) {
            if (!$linea->save()) {
                $errores = $linea->getErrores();
            } else {
                $alertas = $linea->getAlertas();
                $linea = new AlbaranesLineas($datos['IDLinea']);
            }
        }
        break;

    case 'cierre':
        include_once '../modules/ExpedirLineas/ExpedirLineasController.class.php';

        $albaran = new AlbaranesCab($datos['IDAlbaran']);
        $albaran->confirma();
        $expedir = new ExpedirLineasController();
        $expedir->cargaLineasAlbaran($datos['IDAlbaran'], 0);
        //$albaran->expide();
        break;
}

$albaran = new AlbaranesCab($datos['IDAlbaran']);
$lineaAlbaran = $linea->iterator();
$lineaAlbaran['Codigo'] = $linea->getIDArticulo()->getCodigo();

$status = 'ok';
if (count($errores))
    $status = "error";
if (count($alertas))
    $status = "alerta";

$resultado = array(
    'status' => $status,
    'accion' => $accion,
    'linea' => $lineaAlbaran,
    'albaran' => $albaran->iterator(),
    'errores' => $errores,
    'alertas' => $alertas,
);

unset($linea);
unset($albaran);

$tag = json_encode($resultado);

echo $tag;