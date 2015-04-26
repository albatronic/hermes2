#!/usr/bin/php
<?php
/**
 * UTILIDAD PARA CREAR/BORRAR BASES DE DATOS Y TABLAS
 * EN BASE A ARCHIVOS DE CONFIGURACION YAML
 *
 *  * Crea una base de datos y sus tablas a partir del esquema yml
 *  * Carga datos en las tablas a partir de los fixtures yml
 *  * Crea y da permisos a usuarios
 *
 */
// Notificar solamente errores de ejecución
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (file_exists("../yaml/lib/sfYaml.php")) {
    include "../yaml/lib/sfYaml.php";
} else {
    die("NO EXISTE LA CLASE PARA LEER ARCHIVOS YAML\n");
}

// ---------------------------------------------------------------
// ACTIVAR EL AUTOLOADER DE CLASES Y FICHEROS A INCLUIR
// ---------------------------------------------------------------
include_once "Autoloader.class.php";
$path = str_replace("/bin/albatronic", "", __DIR__);
$path = str_replace("\\bin\\albatronic", "", $path); // Para el caso de msdos
Autoloader::setCacheFilePath($path . '/tmp/class_path_cache.txt');
Autoloader::excludeFolderNamesMatchingRegex('/^CVS|\..*$/');
Autoloader::setClassPaths(array(
    __DIR__ . "/",
    $path . '/entities/methods/',
    $path . '/entities/models/',
    $path . '/entities/abstract/',
));
spl_autoload_register(array('Autoloader', 'loadClass'));

$config = sfYaml::load('../../config/config.yml');
$_SESSION['conections'] = $config['config']['conections'];
$_SESSION['debug'] = $config['config']['debug'];
$_SESSION['produccion'] = (strtolower($config['config']['enviroment']) == 'prod');
$_SESSION['idiomas']['actual'] = 0;
$_SESSION['usuarioPortal']['Id'] = 1;

if ($argc < 2) {
    Fab::usage();
} else {
    if (!$_SESSION['produccion']) {
        Fab::interpreta($argv);
    } else {
        echo "No disponible en entorno de produccion\n";
    }
}
