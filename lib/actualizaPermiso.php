<?php

/*
 * ACTUALIZA LOS PERMISOS DE ACCESO A LAS APPS DEL ERP
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 14.04.2013
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
$filtro = "IdPerfil='{$v['idPerfil']}' and NombreModulo='{$v['nombreModulo']}'";
$permiso = new Permisos();
$rows = $permiso->cargaCondicion("Id,Funcionalidades", $filtro);
$idPermiso = $rows[0]['Id'];

if ($idPermiso) {
    if ($v['valor'] == 0) {
        // Quitar permiso
        $permisos = str_replace($v['permiso'], "", $rows[0]['Funcionalidades']);
        // Quitar la eventual coma inicial
        if (substr($permisos,0,1) == ",")
            $permisos = substr($permisos, 1);
        // Quitar dobles comas
        $permisos = str_replace(",,", ",", $permisos);
        // Quito la eventual coma final
        if (substr($permisos, -1) == ",")
            $permisos = substr($permisos, 0, -1);
    } else {
        // Poner permiso
        if (strpos($rows[0]['Funcionalidades'], $v['permiso']) === false)
            $permisos = $rows[0]['Funcionalidades'] . "," . $v['permiso'];
        // Quitar la eventual coma inicial
        if (substr($permisos,0,1) == ",")
            $permisos = substr($permisos, 1);
    }
    $permiso = new Permisos($idPermiso);
    $permiso->setFuncionalidades($permisos);
    $permiso->save();
}

unset($permiso);

$tag = "";

echo $tag;
?>
