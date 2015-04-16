<?php

/*
 * DEVUELVE UN ARTICULO EN EL FORMATO
 * INDICADO EN EL PARÁMETRO 'formato'
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 02.11.2013
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

$v = $_GET;

$formato = strtoupper($v['formato']);
$columna = trim($_GET['columna']);
$valor = trim($_GET['valor']);

if ($columna === '') {
    // Busco el artículo por código o por EAN
    $filtro = "(Codigo='{$valor}' or CodigoEAN='{$valor}') and Vigente='1'";
} else {
    // Busco el artículo por la columna indicada
    $filtro = "{$columna}='{$valor}' and Vigente='1'";
}

$articulo = new Articulos();
$rows = $articulo->cargaCondicion("IDArticulo",$filtro);
$articulo = new Articulos($rows[0]['IDArticulo']);
$array = $articulo->iterator();
$array['Iva'] = $articulo->getIDIva()->getIva();
$array['PvpConImpuestos'] = $articulo->getPrecioVentaConImpuestos();
unset($articulo);

switch ($formato) {
    case '':
    case 'JSON':
        $tag = json_encode($array);
        break;
    default:
        $tag = "";
        break;
}

echo $tag;
?>
