<?php

/*
 * Actualiza la tabla de portes
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 29.03.2013
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

$tabla = new TablaPortes();
$rows = $tabla->cargaCondicion("Id","IDZona='{$v['idZona']}' and IDAgencia='{$v['idAgencia']}' and Kilos='{$v['kilos']}'");
$id = $rows[0]['Id'];

if ($id!=0) {
    // Ya existía, actualizo
    $tabla = new TablaPortes($id);
    $tabla->setImporte($v['importe']);
    $tabla->save();
} else {
    // No existe, creo
    $tabla->setIDAgencia($v['idAgencia']);
    $tabla->setIDZona($v['idZona']);
    $tabla->setKilos($v['kilos']);
    $tabla->setImporte($v['importe']);
    $tabla->create();
}

$tag = $tabla->getErrores();
$tag = $tag[0];
unset($tabla);

echo $tag;
?>
