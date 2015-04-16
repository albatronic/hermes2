<?php

/**
 * GESTION AJAX DE LAS LINEAS DE TICKETS
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.05.2011
 */
session_start();

if (!file_exists('../config/config.yml')) {
    echo "NO EXISTE EL FICHERO DE CONFIGURACION";
    exit;
}

if (file_exists("../bin/yaml/lib/sfYaml.php")) {
    include "../bin/yaml/lib/sfYaml.php";
} else {
    echo "NO EXISTE LA CLASE PARA LEER ARCHIVOS YAML";
    exit;
}

// ---------------------------------------------------------------
// CARGO LOS PARAMETROS DE CONFIGURACION.
// ---------------------------------------------------------------
$config = sfYaml::load('../config/config.yml');
$app = $config['config']['app'];

// ---------------------------------------------------------------
// ACTIVAR EL AUTOLOADER DE CLASES Y FICHEROS A INCLUIR
// ---------------------------------------------------------------
define("APP_PATH", $_SERVER['DOCUMENT_ROOT'] . $app['path'] . "/");
include_once "../" . $app['framework'] . "Autoloader.class.php";
Autoloader::setCacheFilePath(APP_PATH . 'tmp/class_path_cache.txt');
Autoloader::excludeFolderNamesMatchingRegex('/^CVS|\..*$/');
Autoloader::setClassPaths(array(
    '../' . $app['framework'],
    '../entities/',
    '../lib/',
));
spl_autoload_register(array('Autoloader', 'loadClass'));

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
?>
