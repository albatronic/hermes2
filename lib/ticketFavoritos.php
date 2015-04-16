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
?>
