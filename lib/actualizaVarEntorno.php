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
?>
