<?php

/*
 * ACTUALIZA UNA COLUMNA DE UNA ENTIDAD
 *
 * Es llamado por AJAX
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica ALBATRONIC
 * @since 27.05.2011
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
$columna = $v['columna'];

// Actualiza la columna en el idioma actual
$objeto = new $entidad($idEntidad);
$objeto->{"set$columna"}($v['valor']);
if ($objeto->save() && ($columna == 'Publish' || $columna == 'Privacy')) {
    // Actualiza la url amigable
    $urlAmigable = CpanUrlAmigables::sincroniza($objeto);
    // Actualiza la tabla de búsquedas
    $variables = new CpanVariables("Mod", "Env", $entidad);
    if ($variables->getNode('searchable')) {
        $search = new CpanSearch();
        $search->actualiza($objeto);
        unset($search);
    }
}
unset($objeto);

// Actualiza la columna en el resto de idiomas
if ($_SESSION['idiomas']['actual'] == 0) {

    // Recorro los idiomas adicionales
    foreach ($_SESSION['idiomas']['disponibles'] as $key => $value) {
        if ($key > 0) {
            $_SESSION['idiomas']['actual'] = $key;
            $objeto = new $entidad($idEntidad);
            $objeto->{"set$columna"}($v['valor']);
            if ($objeto->save() && ($columna == 'Publish' || $columna == 'Privacy')) {
                // Actualiza la url amigable
                $urlAmigable = CpanUrlAmigables::sincroniza($objeto);
                // Actualiza la tabla de búsquedas
                $variables = new CpanVariables("Mod", "Env", $entidad);
                if ($variables->getNode('searchable')) {
                    $search = new CpanSearch();
                    $search->actualiza($objeto);
                    unset($search);
                }
            }
            unset($objeto);
        }
    }
    $_SESSION['idiomas']['actual'] = 0;
}

$tag = "";

echo $tag;
?>
