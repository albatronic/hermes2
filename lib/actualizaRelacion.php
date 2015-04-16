<?php

/*
 * Actualiza la relación N a M entre entidades e id de entidades
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 03.02.2013
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

$relacion = new CpanRelaciones();
$idRelacion = $relacion->getIdRelacion($v['entidadOrigen'], $v['idOrigen'], $v['entidadDestino'], $v['idDestino']);

if ($v['onOff']) {
    // Hacer relacion
    if (!$idRelacion) {
        $relacion = new CpanRelaciones();
        $relacion->setEntidadOrigen($v['entidadOrigen']);
        $relacion->setIdEntidadOrigen($v['idOrigen']);
        $relacion->setEntidadDestino($v['entidadDestino']);
        $relacion->setIdEntidadDestino($v['idDestino']);
        $relacion->setPublish(1);
        $relacion->create();
    }
} else {
    // Quitar la relación
    if ($idRelacion) {
        $relacion = new CpanRelaciones($idRelacion);
        $relacion->erase();
    }
}

unset($relacion);

$tag = "";

echo $tag;