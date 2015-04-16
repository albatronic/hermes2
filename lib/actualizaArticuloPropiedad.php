<?php

/*
 * Actualiza la tripleta articulo-propiedad-valor
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 10.05.2013
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

$relacion = new ArticulosPropiedades();
$rows = $relacion->cargaCondicion("Id", "IDArticulo='{$v['idArticulo']}' and IDPropiedad='{$v['idPropiedad']}'");
$idRelacion = $rows[0]['Id'];

if (!$idRelacion) {
    // Hacer relacion       
    $relacion = new ArticulosPropiedades();
    $relacion->setIDArticulo($v['idArticulo']);
    $relacion->setIDPropiedad($v['idPropiedad']);
    $relacion->setIDValor($v['idValor']);
    $relacion->setPublish(1);
    $relacion->create();
} else {
    if ($v['idValor'] > 0) {
        // Actualizar la relación
        $relacion = new ArticulosPropiedades($idRelacion);
        $relacion->setIDValor($v['idValor']);
        $relacion->save();
    } else {
        // Borrar la relación
        $relacion = new ArticulosPropiedades($idRelacion);
        $relacion->erase();
    }
}

$tag = $relacion->getErrores();
$tag = $tag[0];

unset($relacion);

echo $tag;
?>
