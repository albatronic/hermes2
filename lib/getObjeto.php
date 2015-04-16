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

$v = $_GET;

$entidad = $v['entidad'];
$idEntidad = $v['idEntidad'];
$formato = strtoupper($v['formato']);
$columna = $v['columna'];
$valor = $v['valor'];

// Si en columna viene valor es que tengo que
// buscar por columna y valor y no por id
if (isset($v['columna'])) {
    $objeto = new $entidad();
    $objeto = $objeto->find($columna, $valor);
}
else {
    $objeto = new $entidad($idEntidad);
}

switch ($formato) {
    case '':
    case 'JSON':
        $tag = $objeto->getJson();
        break;
    default:
        $tag = "";
        break;
}

unset($objeto);

echo $tag;
?>
