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

$familia = new Familias();
$arbol = $familia->getArbolHijos(array('conArticulos'=>true,'conImagenes'=>true),'','',"MostrarEnTpv='1'", "OrdenTpv ASC");

echo json_encode($arbol);
?>
