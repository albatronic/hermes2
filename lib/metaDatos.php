<?php

/*
 * CREA O BORRA METADATOS
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 21.09.2013
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
$metadato = $v['metadato'];
$accion = $v['accion'];
$idEntidad = $v['idEntidad'];

$tag = 0;
switch ($accion) {
    case 'B':
        $meta = new CpanMetaData();
        $meta->queryDelete("Entity='{$entidad}' and Name='{$metadato}'");
        $tag = count($meta->getErrores()) == 0;
        unset($meta);
        break;
    case 'C':
        $meta = new CpanMetaData();
        $rows = $meta->cargaCondicion("Id", "Entity='{$entidad}' and Name='{$metadato}'");
        if (count($rows) == 0) {
            $meta->setEntity($entidad);
            $meta->setIdEntity($idEntidad);
            $meta->setName($metadato);
            $meta->create();
            $tag = (count($meta->getErrores()) == 0);
        } else $tag = 2;
        unset($meta);
        break;
}

echo $tag;
?>
